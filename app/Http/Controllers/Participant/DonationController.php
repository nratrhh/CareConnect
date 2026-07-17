<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Donation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class DonationController extends Controller
{
    public function create($id)
    {
        $event = Event::with('fundraisingCampaign')->findOrFail($id);
        
        if (!$event->fundraisingCampaign) {
            return redirect()->back()->with('error', 'This event does not accept donations.');
        }

        return view('participant.events.donate', [
            'event' => $event,
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'event_id' => 'required|exists:event,event_id'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        // Disable SSL verification for local development (Test Mode only)
        Stripe::setVerifySslCerts(false);

        // Add RM 0.50 transaction fee
        $fee = 0.50;
        $totalAmount = $request->amount + $fee;

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($totalAmount * 100), // Stripe uses cents
                'currency' => 'myr',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'event_id' => $request->event_id,
                    'participant_id' => Auth::guard('participant')->id()
                ]
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_intent_id' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        $participant_id = Auth::guard('participant')->id();
        $event = Event::with('fundraisingCampaign')->findOrFail($id);

        $donation = Donation::create([
            'fundraising_campaign_id' => $event->fundraisingCampaign->fundraising_campaign_id,
            'participant_id' => $participant_id,
            'amount' => $request->amount,
            'payment_intent_id' => $request->payment_intent_id,
            'status' => 'succeeded', // Assuming success if reached here after JS confirmation
            'payment_method' => $request->payment_method,
            'donation_date' => now()
        ]);

        // Increment the campaign's collected amount
        $event->fundraisingCampaign->increment('collected_amount', $request->amount);

        // ─── Notify Participant ───
        Notification::notifyParticipant(
            $participant_id,
            'donation_success',
            'Donation Successful!',
            "Thank you! Your donation of RM " . number_format($request->amount, 2) . " for \"{$event->title}\" has been received.",
            'heart',
            route('participant.activities.receipt', $donation->donation_id)
        );

        // ─── Notify All Admins ───
        $participantName = Auth::guard('participant')->user()->name;
        Notification::notifyAllAdmins(
            'new_donation',
            'New Donation Received',
            "{$participantName} donated RM " . number_format($request->amount, 2) . " to \"{$event->title}\".",
            'dollar-sign',
            route('admin.fundraising.index') . '?show_receipt=' . $donation->donation_id
        );

        return response()->json([
            'success' => true,
            'redirect' => route('participant.events.donate.simulation', $donation->donation_id)
        ]);
    }

    public function simulation($donation_id)
    {
        $donation = Donation::with('fundraisingCampaign.event', 'participant')->findOrFail($donation_id);
        
        // Ensure user owns this donation
        if ($donation->participant_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        return view('participant.events.payment_simulation', [
            'donation' => $donation,
            'event' => $donation->fundraisingCampaign->event
        ]);
    }

    public function success($donation_id)
    {
        $donation = Donation::with('fundraisingCampaign.event', 'participant')->findOrFail($donation_id);
        
        if ($donation->participant_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        return view('participant.events.payment_success', [
            'donation' => $donation,
            'event' => $donation->fundraisingCampaign->event
        ]);
    }

    // ─── Billplz FPX Methods ─────────────────────────────────

    /**
     * Create a Billplz bill for FPX bank transfer and redirect to payment page.
     */
    public function donateFPX(Request $request, $id)
    {
        $request->validate([
            'amount'    => 'required|numeric|min:1',
            'bank_code' => 'nullable|string',
        ]);

        $participant = Auth::guard('participant')->user();
        $event = Event::with('fundraisingCampaign')->findOrFail($id);

        if (!$event->fundraisingCampaign) {
            return redirect()->back()->with('error', 'This event does not accept donations.');
        }

        $billplzUrl = config('services.billplz.url');
        $apiKey = config('services.billplz.api_key');
        $collectionId = config('services.billplz.collection_id');

        // Amount in cents (Billplz uses cents for MYR). Add RM 0.50 transaction fee.
        $fee = 0.50;
        $totalAmount = $request->amount + $fee;
        $amountInCents = (int)($totalAmount * 100);

        // Map bank codes to readable names
        $bankNames = [
            'MBBEMYKL' => 'Maybank',
            'CIBBMYKL' => 'CIMB Bank',
            'PBBEMYKL' => 'Public Bank',
            'RHBBMYKL' => 'RHB Bank',
            'HLBBMYKL' => 'Hong Leong Bank',
            'BIMBMYKL' => 'Bank Islam',
            'AMMBMYKL' => 'AmBank',
            'BKRMMYKL' => 'Bank Rakyat',
        ];

        $bankCode = $request->bank_code;
        $bankName = $bankNames[$bankCode] ?? 'FPX';

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($apiKey, '')
                ->post("{$billplzUrl}/api/v3/bills", [
                    'collection_id' => $collectionId,
                    'email'         => $participant->email,
                    'name'          => $participant->name,
                    'amount'        => $amountInCents,
                    'description'   => 'Donation for ' . $event->title,
                    'callback_url'  => route('participant.fpx.callback'),
                    'redirect_url'  => route('participant.fpx.return'),
                ]);

            if (!$response->successful()) {
                Log::error('Billplz bill creation failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return redirect()->back()->with('error', 'Failed to create FPX payment. Please try again.');
            }

            $bill = $response->json();

            // Save a pending donation record
            $donation = Donation::create([
                'fundraising_campaign_id'    => $event->fundraisingCampaign->fundraising_campaign_id,
                'participant_id' => $participant->participant_id,
                'amount'         => $request->amount,
                'status'         => 'pending',
                'payment_method' => 'FPX: ' . $bankName,
                'donation_date'  => now(),
                'bill_code'      => $bill['id'],
            ]);

            // Redirect to Billplz — auto_submit skips Billplz bank selection page
            $redirectUrl = $bill['url'];
            if ($bankCode) {
                $redirectUrl .= '?auto_submit=true';
            }
            return redirect($redirectUrl);

        } catch (\Exception $e) {
            Log::error('Billplz FPX error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment service unavailable. Please try again later.');
        }
    }

    /**
     * Handle Billplz background callback (server-to-server).
     * This is called by Billplz after payment is processed.
     */
    public function fpxCallback(Request $request)
    {
        $billCode = $request->input('id');
        $paid     = $request->input('paid');
        $transactionId = $request->input('transaction_id');

        Log::info('Billplz FPX Callback received', $request->all());

        $donation = Donation::with(['participant', 'fundraisingCampaign.event'])->where('bill_code', $billCode)->first();

        if (!$donation) {
            Log::warning('Billplz callback: Donation not found', ['bill_code' => $billCode]);
            return response('OK', 200);
        }

        if ($paid === 'true' || $paid === true) {
            $donation->update([
                'status'         => 'succeeded',
                'transaction_id' => $transactionId,
            ]);

            // Increment the campaign's collected amount
            $donation->fundraisingCampaign->increment('collected_amount', $donation->amount);

            // ─── Notify Participant ───
            $eventTitle = $donation->fundraisingCampaign->event->title ?? 'Campaign';
            Notification::notifyParticipant(
                $donation->participant_id,
                'donation_success',
                'Donation Successful!',
                "Thank you! Your donation of RM " . number_format($donation->amount, 2) . " for \"{$eventTitle}\" has been received.",
                'heart',
                route('participant.activities.receipt', $donation->donation_id)
            );

            // ─── Notify All Admins ───
            $participantName = $donation->participant->name ?? 'A participant';
            Notification::notifyAllAdmins(
                'new_donation',
                'New Donation Received',
                "{$participantName} donated RM " . number_format($donation->amount, 2) . " to \"{$eventTitle}\".",
                'dollar-sign',
                route('admin.fundraising.index') . '?show_receipt=' . $donation->donation_id
            );
        } else {
            $donation->update([
                'status'         => 'failed',
                'transaction_id' => $transactionId,
            ]);
        }

        return response('OK', 200);
    }

    /**
     * Handle user redirect back from Billplz after payment.
     */
    public function fpxReturn(Request $request)
    {
        $billCode = $request->input('billplz')['id'] ?? $request->input('id');
        $paid     = $request->input('billplz')['paid'] ?? $request->input('paid');

        $donation = Donation::with('fundraisingCampaign.event', 'participant')
            ->where('bill_code', $billCode)
            ->first();

        if (!$donation) {
            return redirect()->route('participant.events.index')
                ->with('error', 'Donation record not found.');
        }

        // Verify ownership
        if ($donation->participant_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        if ($paid === 'true' || $paid === true) {
            // If callback hasn't processed yet, update status and send notifications here
            if ($donation->status !== 'succeeded') {
                $donation->update([
                    'status' => 'succeeded',
                ]);
                $donation->fundraisingCampaign->increment('collected_amount', $donation->amount);

                // ─── Notify Participant ───
                $eventTitle = $donation->fundraisingCampaign->event->title ?? 'Campaign';
                Notification::notifyParticipant(
                    $donation->participant_id,
                    'donation_success',
                    'Donation Successful!',
                    "Thank you! Your donation of RM " . number_format($donation->amount, 2) . " for \"{$eventTitle}\" has been received.",
                    'heart',
                    route('participant.activities.receipt', $donation->donation_id)
                );

                // ─── Notify All Admins ───
                $participantName = $donation->participant->name ?? 'A participant';
                Notification::notifyAllAdmins(
                    'new_donation',
                    'New Donation Received',
                    "{$participantName} donated RM " . number_format($donation->amount, 2) . " to \"{$eventTitle}\".",
                    'dollar-sign',
                    route('admin.fundraising.index') . '?show_receipt=' . $donation->donation_id
                );
            }

            return redirect()->route('participant.events.donate.simulation', $donation->donation_id)->with('from_bank', true);
        } else {
            if ($donation->status !== 'failed') {
                $donation->update(['status' => 'failed']);
            }

            return redirect()->route('participant.events.index')
                ->with('error', 'FPX payment was not completed. Please try again.');
        }
    }
}
