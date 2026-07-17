<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ParticipantDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $thirtyDaysFromNow = Carbon::now()->addDays(30);

        $allEvents = Event::where('status', 'active')
            ->where(function($q) {
                $q->whereHas('volunteerEvent')->orWhereHas('fundraisingCampaign');
            })
            ->with(['volunteerEvent.applications', 'fundraisingCampaign.donations'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Active Events: Starts within 30 days AND hasn't ended yet
        $activeEvents = $allEvents->filter(function ($event) use ($now, $thirtyDaysFromNow) {
            $today = $now->copy()->startOfDay();
            if ($event->volunteerEvent) {
                return $event->volunteerEvent->eventDate >= $today && $event->volunteerEvent->eventDate <= $thirtyDaysFromNow;
            }
            if ($event->fundraisingCampaign) {
                return $event->fundraisingCampaign->end_date >= $today && $event->fundraisingCampaign->start_date <= $thirtyDaysFromNow;
            }
            return true; 
        })->take(6);

        // Upcoming Events: Starts after 30 days
        $upcomingEvents = $allEvents->filter(function ($event) use ($thirtyDaysFromNow) {
            if ($event->volunteerEvent) {
                return $event->volunteerEvent->eventDate > $thirtyDaysFromNow;
            }
            if ($event->fundraisingCampaign) {
                return $event->fundraisingCampaign->start_date > $thirtyDaysFromNow;
            }
            return false;
        });

        // Sort active events by urgency (end_date / eventDate)
        $getActiveEventDate = function ($event) {
            if ($event->volunteerEvent) {
                return Carbon::parse($event->volunteerEvent->eventDate);
            }
            if ($event->fundraisingCampaign) {
                return Carbon::parse($event->fundraisingCampaign->end_date);
            }
            return Carbon::parse($event->created_at);
        };

        // Sort upcoming events by launch date (start_date / eventDate)
        $getUpcomingEventDate = function ($event) {
            if ($event->volunteerEvent) {
                return Carbon::parse($event->volunteerEvent->eventDate);
            }
            if ($event->fundraisingCampaign) {
                return Carbon::parse($event->fundraisingCampaign->start_date);
            }
            return Carbon::parse($event->created_at);
        };

        // Apply sorting
        $activeEvents = $activeEvents->sortBy($getActiveEventDate)->values();
        $upcomingEvents = $upcomingEvents->sortBy($getUpcomingEventDate)->values();

        // Fetch recent activities
        $participantId = auth()->guard('participant')->id();

        $recentDonations = \App\Models\Donation::where('participant_id', $participantId)
            ->where('status', 'succeeded')
            ->with('fundraisingCampaign.event')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return (object)[
                    'type' => 'donation',
                    'title' => 'Donation of RM ' . number_format($donation->amount, 2) . ' processed',
                    'subtitle' => $donation->fundraisingCampaign->event->title ?? 'Unknown Event',
                    'date' => $donation->created_at,
                    'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                    'color' => '#10B981', // emerald-500
                    'bg' => '#D1FAE5',    // emerald-100
                ];
            });

        $recentApplications = \App\Models\VolunteerApplication::where('participant_id', $participantId)
            ->with('volunteerEvent.event')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($app) {
                $title = '';
                $color = '';
                $bg = '';
                $icon = '';

                if ($app->status === 'approved') {
                    $title = 'Application <span style="color: #2563EB; font-weight: 700;">Approved</span>';
                    $color = '#2563EB'; // blue-600
                    $bg = '#DBEAFE';    // blue-100
                    $icon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
                } elseif ($app->status === 'pending') {
                    $title = 'New application <span style="font-weight: 700;">submitted</span>';
                    $color = '#475569'; // slate-600
                    $bg = '#F1F5F9';    // slate-100
                    $icon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
                } elseif ($app->status === 'declined' || $app->status === 'cancelled') {
                    $statusStr = ucfirst($app->status);
                    $title = 'Application <span style="color: #DC2626; font-weight: 700;">' . $statusStr . '</span>';
                    $color = '#DC2626'; // red-600
                    $bg = '#FEE2E2';    // red-100
                    $icon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                }

                return (object)[
                    'type' => 'application',
                    'title' => $title,
                    'subtitle' => $app->volunteerEvent->event->title ?? 'Unknown Event',
                    'date' => $app->status === 'pending' ? $app->created_at : $app->updated_at,
                    'icon' => $icon,
                    'color' => $color,
                    'bg' => $bg,
                ];
            });

        $recentActivities = $recentDonations->concat($recentApplications)
            ->sortByDesc('date')
            ->take(5)
            ->values();

        return view('participant.dashboard', [
            'activeEvents' => $activeEvents,
            'upcomingEvents' => $upcomingEvents,
            'recentActivities' => $recentActivities
        ]);
    }
}
