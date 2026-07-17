<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class ParticipantEventController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $search = $request->query('search');

        $query = Event::select('event.*')
            ->leftJoin('volunteer_event', 'event.event_id', '=', 'volunteer_event.event_id')
            ->leftJoin('fundraising_campaign', 'event.event_id', '=', 'fundraising_campaign.event_id')
            ->where('event.status', 'active')
            ->where(function($q) {
                $q->whereNotNull('volunteer_event.volunteer_event_id')
                  ->orWhereNotNull('fundraising_campaign.fundraising_campaign_id');
            })
            ->with(['volunteerEvent.applications', 'fundraisingCampaign.donations']);

        if ($type === 'fundraising') {
            $query->whereNotNull('fundraising_campaign.fundraising_campaign_id');
        } elseif ($type === 'volunteer') {
            $query->whereNotNull('volunteer_event.volunteer_event_id');
        } elseif ($type === 'active') {
            $thirtyDaysFromNow = now()->addDays(30);
            $today = now()->startOfDay();
            $query->where(function($q) use ($thirtyDaysFromNow, $today) {
                $q->where(function($sq) use ($thirtyDaysFromNow, $today) {
                    $sq->where('volunteer_event.eventDate', '>=', $today)
                       ->where('volunteer_event.eventDate', '<=', $thirtyDaysFromNow);
                })->orWhere(function($sq) use ($thirtyDaysFromNow, $today) {
                    $sq->where('fundraising_campaign.end_date', '>=', $today)
                       ->where('fundraising_campaign.start_date', '<=', $thirtyDaysFromNow);
                });
            });
        } elseif ($type === 'upcoming') {
            $thirtyDaysFromNow = now()->addDays(30);
            $query->where(function($q) use ($thirtyDaysFromNow) {
                $q->where('volunteer_event.eventDate', '>', $thirtyDaysFromNow)
                  ->orWhere('fundraising_campaign.start_date', '>', $thirtyDaysFromNow);
            });
        }

        if ($search) {
            $query->where('event.title', 'like', "%{$search}%");
        }

        // Sort chronologically (closest dates first)
        $events = $query->orderByRaw('COALESCE(volunteer_event.eventDate, fundraising_campaign.end_date) ASC')->paginate(9);

        return view('participant.events.index', [
            'events' => $events,
            'currentType' => $type,
            'search' => $search
        ]);
    }

    public function show($id)
    {
        $event = Event::with(['volunteerEvent.applications', 'fundraisingCampaign.donations'])->findOrFail($id);
        
        $hasApplied = false;
        $isFull = false;
        
        if (auth()->guard('participant')->check() && $event->volunteerEvent) {
            $hasApplied = \App\Models\VolunteerApplication::where('volunteer_event_id', $event->volunteerEvent->volunteer_event_id)
                ->where('participant_id', auth()->guard('participant')->id())
                ->whereNotIn('status', ['cancelled'])
                ->exists();
                
            $approvedCount = $event->volunteerEvent->applications->where('status', 'approved')->count();
            $isFull = $event->volunteerEvent->capacity > 0 && $approvedCount >= $event->volunteerEvent->capacity;
        }

        return view('participant.events.show', [
            'event' => $event,
            'hasApplied' => $hasApplied,
            'isFull' => $isFull
        ]);
    }
}
