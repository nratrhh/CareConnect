<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\FundraisingCampaign;
use App\Models\Notification;
use App\Models\VolunteerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::select('event.*')
            ->leftJoin('volunteer_event', 'event.event_id', '=', 'volunteer_event.event_id')
            ->leftJoin('fundraising_campaign', 'event.event_id', '=', 'fundraising_campaign.event_id')
            ->with(['volunteerEvent', 'fundraisingCampaign'])
            ->orderByRaw('COALESCE(volunteer_event.eventDate, fundraising_campaign.end_date) ASC')
            ->get();

        return view('admin.events', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'eventShortDesc' => 'required|string|max:150',
            'eventLongDesc'  => 'required|string',
            'status'        => 'required|string|in:active,draft,completed',
            'event_type'    => 'required|in:fundraising,volunteer',
            'eventCategory' => 'required|string|max:255',
            'eventImg'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('eventImg')) {
            $imagePath = $request->file('eventImg')->store('events', 'public');
        }

        $event = Event::create([
            'admin_id'      => Auth::guard('admin')->id(),
            'title'         => $request->title,
            'eventImg'      => $imagePath,
            'eventCategory' => $request->eventCategory,
            'eventShortDesc'=> $request->eventShortDesc,
            'eventLongDesc' => $request->eventLongDesc,
            'status'        => $request->status,
        ]);

        // Create related record based on event type
        if ($request->event_type === 'fundraising') {
            $request->validate([
                'target_amount'    => 'required|numeric|min:0',
                'start_date'       => 'required|date',
                'end_date'         => 'required|date|after_or_equal:start_date',
            ]);

            FundraisingCampaign::create([
                'event_id'         => $event->event_id,
                'target_amount'    => $request->target_amount,
                'collected_amount' => 0,
                'start_date'       => $request->start_date,
                'end_date'         => $request->end_date,
            ]);
        } else {
            $request->validate([
                'capacity' => 'required|integer|min:1',
                'eventDate' => 'required|date',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'location' => 'required|string|max:255',
                'location_details' => 'nullable|string',
                'benefits' => 'nullable|array',
            ]);

            VolunteerEvent::create([
                'event_id' => $event->event_id,
                'capacity' => $request->capacity,
                'eventDate' => $request->eventDate,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'location_details' => $request->location_details,
                'benefits' => $request->benefits ?? [],
            ]);
        }

        // ─── Notify All Participants about new event ───
        if ($event->status === 'active') {
            $typeLabel = $request->event_type === 'fundraising' ? 'Fundraising Campaign' : 'Volunteer Event';
            
            // Determine if it's "Now Open" or "Coming Soon" based on 30-day logic
            $targetDate = $request->event_type === 'fundraising' ? $request->start_date : $request->eventDate;
            $isComingSoon = \Carbon\Carbon::parse($targetDate)->gt(now()->addDays(30));
            $statusText = $isComingSoon ? "is coming soon!" : "is now open.";

            \App\Models\Notification::notifyAllParticipants(
                'new_event',
                'New ' . $typeLabel . '!',
                "A new {$typeLabel} \"{$event->title}\" {$statusText} Check it out!",
                $request->event_type === 'fundraising' ? 'dollar-sign' : 'users',
                route('participant.events.show', $event->event_id)
            );
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = Event::with(['volunteerEvent.applications.participant', 'fundraisingCampaign.donations'])
            ->findOrFail($id);
        
        $event->append('formatted_id');

        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::with(['volunteerEvent', 'fundraisingCampaign'])->findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'eventShortDesc' => 'required|string|max:150',
            'eventLongDesc'  => 'required|string',
            'status'        => 'required|string|in:active,draft,completed',
            'eventCategory' => 'nullable|string|max:255',
            'eventImg'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('eventImg')) {
            $event->eventImg = $request->file('eventImg')->store('events', 'public');
        }

        // Capture old status before update
        $oldStatus = $event->status;

        $event->update([
            'title'         => $request->title,
            'eventImg'      => $event->eventImg,
            'eventCategory' => $request->eventCategory ?? $event->eventCategory,
            'eventShortDesc'=> $request->eventShortDesc,
            'eventLongDesc' => $request->eventLongDesc,
            'status'        => $request->status,
        ]);

        // Update related records
        if ($event->fundraisingCampaign) {
            $request->validate([
                'target_amount'    => 'nullable|numeric|min:0',
                'start_date'       => 'nullable|date',
                'end_date'         => 'nullable|date|after_or_equal:start_date',
            ]);

            $event->fundraisingCampaign->update([
                'target_amount'    => $request->target_amount ?? $event->fundraisingCampaign->target_amount,
                'start_date'       => $request->start_date ?? $event->fundraisingCampaign->start_date,
                'end_date'         => $request->end_date ?? $event->fundraisingCampaign->end_date,
            ]);
        }

        if ($event->volunteerEvent) {
            $request->validate([
                'capacity' => 'nullable|integer|min:1',
                'eventDate' => 'nullable|date',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'location' => 'nullable|string|max:255',
                'location_details' => 'nullable|string',
                'benefits' => 'nullable|array',
            ]);

            $event->volunteerEvent->update([
                'capacity' => $request->capacity ?? $event->volunteerEvent->capacity,
                'eventDate' => $request->eventDate ?? $event->volunteerEvent->eventDate,
                'start_time' => $request->start_time ?? $event->volunteerEvent->start_time,
                'end_time' => $request->end_time ?? $event->volunteerEvent->end_time,
                'location' => $request->location ?? $event->volunteerEvent->location,
                'location_details' => $request->location_details ?? $event->volunteerEvent->location_details,
                'benefits' => $request->has('benefits') ? ($request->benefits ?? []) : $event->volunteerEvent->benefits,
            ]);
        }

        // ─── Notify participants if event just became active ───
        if ($request->status === 'active' && $oldStatus !== 'active') {
            $typeLabel = $event->fundraisingCampaign ? 'Fundraising Campaign' : 'Volunteer Event';
            
            // Determine if it's "Now Open" or "Coming Soon" based on 30-day logic
            $targetDate = $event->fundraisingCampaign 
                ? ($request->start_date ?? $event->fundraisingCampaign->start_date)
                : ($request->eventDate ?? $event->volunteerEvent->eventDate);
            
            $isComingSoon = \Carbon\Carbon::parse($targetDate)->gt(now()->addDays(30));
            $statusText = $isComingSoon ? "is coming soon!" : "is now open.";

            \App\Models\Notification::notifyAllParticipants(
                'new_event',
                'New ' . $typeLabel . '!',
                "A new {$typeLabel} \"{$event->title}\" {$statusText} Check it out!",
                $event->fundraisingCampaign ? 'dollar-sign' : 'users',
                route('participant.events.show', $event->event_id)
            );
        }

        // ─── Notify approved volunteers when event is completed (e-certificate) ───
        $hasCertificateBenefit = is_array($event->volunteerEvent?->benefits) && in_array('certificate', $event->volunteerEvent->benefits);
        if ($request->status === 'completed' && $event->volunteerEvent && $hasCertificateBenefit) {
            $approvedApps = $event->volunteerEvent->applications()->where('status', 'approved')->get();
            foreach ($approvedApps as $app) {
                Notification::notifyParticipant(
                    $app->participant_id,
                    'certificate_available',
                    'E-Certificate Available!',
                    "Your e-certificate for \"{$event->title}\" is now ready. View it in My Activities.",
                    'award',
                    route('participant.activities.certificate', $app->application_id)
                );
            }
        }

        $successMessage = 'Event updated successfully.';
        if ($request->status === 'completed' && $event->volunteerEvent && $hasCertificateBenefit && $oldStatus !== 'completed') {
            $successMessage = 'Event updated and E-certificate generated.';
        }

        return redirect()->route('admin.events.index')
            ->with('success', $successMessage);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
