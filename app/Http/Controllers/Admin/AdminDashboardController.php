<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundraisingCampaign;
use App\Models\Donation;
use App\Models\Event;
use App\Models\VolunteerApplication;
use App\Models\VolunteerEvent;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $totalFunds = Donation::where('status', 'succeeded')->sum('amount');
        $totalVolunteers = VolunteerApplication::where('status', 'approved')->count();
        $pendingApplications = VolunteerApplication::where('status', 'pending')->count();
        $totalVolunteerEvents = VolunteerEvent::count();
        $totalFundraisingCampaigns = FundraisingCampaign::count();

        // Top 3 campaigns for Funds Raised breakdown
        $topCampaigns = FundraisingCampaign::with('event')
            ->where('collected_amount', '>', 0)
            ->orderBy('collected_amount', 'desc')
            ->take(3)
            ->get();

        $activeEvents = Event::where('status', 'active')
            ->with(['volunteerEvent', 'fundraisingCampaign'])
            ->get()
            ->filter(function($event) {
                $now = now()->startOfDay();
                if ($event->volunteerEvent && $event->volunteerEvent->eventDate) {
                    return $event->volunteerEvent->eventDate->startOfDay() >= $now;
                }
                if ($event->fundraisingCampaign && $event->fundraisingCampaign->end_date) {
                    return $event->fundraisingCampaign->end_date->startOfDay() >= $now;
                }
                return true;
            })
            ->sortBy(function($event) {
                $now = now();
                if ($event->volunteerEvent && $event->volunteerEvent->eventDate) {
                    return $event->volunteerEvent->eventDate->timestamp;
                }
                if ($event->fundraisingCampaign) {
                    if ($event->fundraisingCampaign->start_date && $event->fundraisingCampaign->start_date > $now) {
                        return $event->fundraisingCampaign->start_date->timestamp;
                    }
                    if ($event->fundraisingCampaign->end_date) {
                        return $event->fundraisingCampaign->end_date->timestamp;
                    }
                }
                return PHP_INT_MAX;
            })
            ->take(4);

        $fundraisingCampaigns = FundraisingCampaign::with('event')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Structured Event data for calendar
        $eventCalendarData = [];
        $now = now()->startOfDay();

        // Volunteer Events
        $volunteerEvents = VolunteerEvent::with('event')->whereNotNull('eventDate')->get();
        foreach ($volunteerEvents as $ve) {
            $eventDate = $ve->eventDate->startOfDay();
            $diff = $now->diffInDays($eventDate, false);
            
            $dDay = '';
            if ($diff == 0) $dDay = 'Happening Today';
            elseif ($diff > 0) $dDay = 'In ' . $diff . ' days';
            else $dDay = abs($diff) . ' days ago';

            $date = $ve->eventDate->format('Y-m-d');
            $eventCalendarData[$date][] = [
                'title' => $ve->event->title ?? 'Volunteer Event',
                'type'  => 'Volunteer',
                'time'  => 'Event Date',
                'dday'  => $dDay
            ];
        }

        // Fundraising Campaigns (Start and End dates)
        $campaigns = FundraisingCampaign::with('event')->get();
        foreach ($campaigns as $c) {
            if ($c->start_date) {
                $startDate = $c->start_date->startOfDay();
                $diff = $now->diffInDays($startDate, false);
                
                $dDay = '';
                if ($diff == 0) $dDay = 'Starts Today';
                elseif ($diff > 0) $dDay = 'In ' . $diff . ' days';
                else $dDay = 'Started ' . abs($diff) . ' days ago';

                $date = $c->start_date->format('Y-m-d');
                $eventCalendarData[$date][] = [
                    'title' => $c->event->title ?? 'Fundraising Campaign',
                    'type'  => 'Fundraising',
                    'time'  => 'Starts',
                    'dday'  => $dDay
                ];
            }
            if ($c->end_date) {
                $endDate = $c->end_date->startOfDay();
                $diff = $now->diffInDays($endDate, false);
                
                $dDay = '';
                if ($diff == 0) $dDay = 'Ends Today';
                elseif ($diff > 0) $dDay = 'Ends in ' . $diff . ' days';
                else $dDay = 'Ended ' . abs($diff) . ' days ago';

                $date = $c->end_date->format('Y-m-d');
                $eventCalendarData[$date][] = [
                    'title' => $c->event->title ?? 'Fundraising Campaign',
                    'type'  => 'Fundraising',
                    'time'  => 'Ends',
                    'dday'  => $dDay
                ];
            }
        }

        // ─── Chart Data: Donations over Last 6 Months ───
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $donations = Donation::where('status', 'succeeded')
            ->where('donation_date', '>=', $sixMonthsAgo)
            ->get();

        $donationChartLabels = [];
        $donationChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $donationChartLabels[] = $date->format('M Y');
            $total = $donations->filter(function($d) use ($date) {
                return \Carbon\Carbon::parse($d->donation_date)->format('Y-m') === $date->format('Y-m');
            })->sum('amount');
            $donationChartData[] = $total;
        }

        // Recent Activities
        $activities = collect([]);

        // 1. Donations
        $recentDonations = Donation::with(['participant', 'fundraisingCampaign.event'])
            ->orderBy('donation_date', 'desc')
            ->take(5)
            ->get();
        foreach ($recentDonations as $d) {
            $campaignName = $d->fundraisingCampaign->event->title ?? 'General Fund';
            $activities->push([
                'name'       => $d->participant->name ?? 'Anonymous',
                'date'       => $d->donation_date ?? $d->created_at,
                'details'    => 'RM ' . number_format($d->amount, 2),
                'event_name' => $campaignName,
                'category'   => 'Donation',
                'status'     => $d->status === 'succeeded' ? 'completed' : $d->status,
            ]);
        }

        // 2. Volunteer Applications
        $recentApps = VolunteerApplication::with(['participant', 'volunteerEvent.event'])
            ->orderBy('applied_at', 'desc')
            ->take(5)
            ->get();
        foreach ($recentApps as $app) {
            $eventName = $app->volunteerEvent->event->title ?? 'Event';
            $skills = 'Event Helper';
            if (!empty($app->skills)) {
                $skills = is_array($app->skills) ? implode(', ', $app->skills) : $app->skills;
            }
            $activities->push([
                'name'       => $app->participant->name ?? 'Unknown',
                'date'       => $app->applied_at ?? $app->created_at,
                'details'    => $skills,
                'event_name' => $eventName,
                'category'   => 'Volunteer',
                'status'     => $app->status,
            ]);
        }

        // 3. New Events Created
        $recentEvents = Event::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        foreach ($recentEvents as $event) {
            $activities->push([
                'name'       => 'System Admin',
                'date'       => $event->created_at,
                'details'    => 'Event Creation',
                'event_name' => $event->title,
                'category'   => 'Event',
                'status'     => $event->status === 'active' ? 'approved' : $event->status,
            ]);
        }

        // Sort by date desc and take top 6
        $recentActivities = $activities->sortByDesc('date')->take(6)->values();

        return view('admin.dashboard', [
            'totalEvents'         => $totalEvents,
            'totalFunds'          => $totalFunds,
            'totalVolunteers'     => $totalVolunteers,
            'pendingApplications' => $pendingApplications,
            'totalVolunteerEvents'=> $totalVolunteerEvents,
            'totalFundraisingCampaigns' => $totalFundraisingCampaigns,
            'activeEvents'        => $activeEvents,
            'fundraising_campaigns' => $fundraisingCampaigns,
            'eventCalendarData'   => $eventCalendarData,
            'topCampaigns'        => $topCampaigns,
            'donationChartLabels' => $donationChartLabels,
            'donationChartData'   => $donationChartData,
            'recentActivities'    => $recentActivities,
        ]);
    }
}