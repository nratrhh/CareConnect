<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantActivityController extends Controller
{
    public function index()
    {
        $participant_id = Auth::guard('participant')->id();

        $donations = Donation::where('participant_id', $participant_id)
            ->with('fundraisingCampaign.event')
            ->orderBy('donation_date', 'desc')
            ->get();

        $applications = VolunteerApplication::where('participant_id', $participant_id)
            ->with('volunteerEvent.event')
            ->orderBy('applied_at', 'desc')
            ->get();

        $totalDonated = $donations->where('status', 'succeeded')->sum('amount');
        $totalVolunteerEvents = $applications->where('status', 'approved')->count();

        return view('participant.activities.index', [
            'donations' => $donations,
            'applications' => $applications,
            'totalDonated' => $totalDonated,
            'totalVolunteerEvents' => $totalVolunteerEvents,
        ]);
    }

    public function receipt($id)
    {
        $participant_id = Auth::guard('participant')->id();
        $donation = Donation::where('participant_id', $participant_id)
            ->with(['fundraisingCampaign.event', 'participant'])
            ->findOrFail($id);

        return view('participant.activities.receipt', [
            'donation' => $donation
        ]);
    }

    public function certificate($id)
    {
        $participant_id = Auth::guard('participant')->id();
        $application = VolunteerApplication::where('participant_id', $participant_id)
            ->with(['volunteerEvent.event', 'participant'])
            ->where('status', 'approved')
            ->whereHas('volunteerEvent.event', function($q) {
                $q->where('status', 'completed');
            })
            ->findOrFail($id);

        return view('participant.activities.certificate', [
            'application' => $application
        ]);
    }
}
