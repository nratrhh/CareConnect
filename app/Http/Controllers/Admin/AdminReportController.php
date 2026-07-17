<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundraisingCampaign;
use App\Models\Donation;
use App\Models\Event;
use App\Models\VolunteerApplication;
use App\Models\VolunteerEvent;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        // Fundraising Report Data
        $fundraisingCampaigns = FundraisingCampaign::with(['event', 'donations.participant'])->get()->map(function ($fundraisingCampaign) {
            $donations = $fundraisingCampaign->donations;
            $topDonor = $donations->where('status', 'succeeded')->sortByDesc('amount')->first();

            return (object) [
                'fundraising_campaign_id'    => $fundraisingCampaign->fundraising_campaign_id,
                'campaign_name'  => $fundraisingCampaign->event->title ?? 'N/A',
                'goal_amount'    => $fundraisingCampaign->target_amount,
                'total_donated'  => $donations->where('status', 'succeeded')->sum('amount'),
                'percentage'     => $fundraisingCampaign->percentage,
                'num_donors'     => $donations->where('status', 'succeeded')->count(),
                'top_donor'      => $topDonor ? ($topDonor->participant->name ?? 'Anonymous') : 'N/A',
                'top_amount'     => $topDonor ? $topDonor->amount : 0,
                'date'           => $fundraisingCampaign->created_at,
                'donors'         => $donations->where('status', 'succeeded')->sortByDesc('amount')->values()->map(function ($d) {
                    return [
                        'name'           => $d->participant->name ?? 'Anonymous',
                        'amount'         => $d->amount,
                        'date'           => $d->donation_date ? $d->donation_date->format('d M Y') : 'N/A',
                        'payment_method' => $d->payment_method ?? 'N/A',
                    ];
                }),
            ];
        });

        // Engagement Report Data
        $volunteerEvents = VolunteerEvent::with(['event', 'applications.participant'])->get()->map(function ($ve) {
            $apps = $ve->applications;
            return (object) [
                'event_name'       => $ve->event->title ?? 'N/A',
                'date'             => $ve->eventDate ?? null,
                'location'         => $ve->location ?? 'N/A',
                'status'           => $ve->event->status ?? 'N/A',
                'capacity'         => $ve->capacity,
                'total_registered' => $apps->count(),
                'approved'         => $apps->where('status', 'approved')->count(),
                'pending'          => $apps->where('status', 'pending')->count(),
                'declined'         => $apps->where('status', 'declined')->count(),
                'volunteers'       => $apps->map(function ($app) {
                    return (object) [
                        'name'       => $app->participant->name ?? 'N/A',
                        'applied_at' => $app->applied_at,
                        'status'     => $app->status,
                    ];
                }),
            ];
        });

        $fundraisingTitles = FundraisingCampaign::with('event')->get()->pluck('event.title')->unique();
        $volunteerTitles = VolunteerEvent::with('event')->get()->pluck('event.title')->unique();

        return view('admin.reports', compact('fundraisingCampaigns', 'volunteerEvents', 'fundraisingTitles', 'volunteerTitles'));
    }

    /**
     * Download a report as CSV
     */
    public function download(Request $request)
    {
        $type = $request->query('type', 'fundraising');

        if ($type === 'fundraising') {
            return $this->downloadFundraisingReport();
        }

        return $this->downloadEngagementReport();
    }

    private function downloadFundraisingReport()
    {
        $fundraisingCampaigns = FundraisingCampaign::with(['event', 'donations.participant'])->get();

        $csv = "Campaign Name,Goal Amount (RM),Total Donations (RM),% Goal Achieved,Number of Donors,Top Donor,Top Donation (RM)\n";

        foreach ($fundraisingCampaigns as $fundraisingCampaign) {
            $donations = $fundraisingCampaign->donations;
            $topDonor = $donations->where('status', 'succeeded')->sortByDesc('amount')->first();

            $csv .= implode(',', [
                '"' . ($fundraisingCampaign->event->title ?? 'N/A') . '"',
                number_format($fundraisingCampaign->target_amount, 2),
                number_format($donations->where('status', 'succeeded')->sum('amount'), 2),
                $fundraisingCampaign->percentage . '%',
                $donations->where('status', 'succeeded')->count(),
                '"' . ($topDonor ? ($topDonor->participant->name ?? 'Anonymous') : 'N/A') . '"',
                $topDonor ? number_format($topDonor->amount, 2) : '0.00',
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="fundraising_report_' . date('Y-m-d') . '.csv"');
    }

    private function downloadEngagementReport()
    {
        $volunteerEvents = VolunteerEvent::with(['event', 'applications.participant'])->get();

        $csv = "Event Name,Date,Location,Status,Capacity,Total Registered,Approved,Pending,Declined\n";

        foreach ($volunteerEvents as $ve) {
            $apps = $ve->applications;

            $csv .= implode(',', [
                '"' . ($ve->event->title ?? 'N/A') . '"',
                $ve->eventDate ? $ve->eventDate->format('Y-m-d') : 'N/A',
                '"' . ($ve->location ?? 'N/A') . '"',
                $ve->event->status ?? 'N/A',
                $ve->capacity,
                $apps->count(),
                $apps->where('status', 'approved')->count(),
                $apps->where('status', 'pending')->count(),
                $apps->where('status', 'declined')->count(),
            ]) . "\n";
        }

        // Add volunteer details
        $csv .= "\n\nDetailed Volunteer List\n";
        $csv .= "Event Name,Volunteer Name,Application Date,Status\n";

        foreach ($volunteerEvents as $ve) {
            foreach ($ve->applications as $app) {
                $csv .= implode(',', [
                    '"' . ($ve->event->title ?? 'N/A') . '"',
                    '"' . ($app->participant->name ?? 'N/A') . '"',
                    $app->applied_at ? $app->applied_at->format('Y-m-d H:i') : 'N/A',
                    $app->status,
                ]) . "\n";
            }
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="engagement_report_' . date('Y-m-d') . '.csv"');
    }
}
