<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundraisingCampaign;
use App\Models\Donation;

class AdminFundraisingController extends Controller
{


    // List all campaigns with donations (admin view)
    public function index()
    {
        $fundraisingCampaigns = FundraisingCampaign::with(['event', 'donations'])
            ->orderBy('start_date', 'asc')
            ->get();

        $donations = Donation::with(['participant', 'fundraisingCampaign.event'])
            ->orderBy('donation_date', 'desc')
            ->get();

        return view('admin.fundraising', compact('fundraisingCampaigns', 'donations'));
    }

    // View receipt for a specific donation (admin)
    public function receipt($donationId)
    {
        $donation = Donation::with(['fundraisingCampaign.event', 'participant'])
            ->findOrFail($donationId);
        return view('admin.donation_receipt', compact('donation'));
    }
}
