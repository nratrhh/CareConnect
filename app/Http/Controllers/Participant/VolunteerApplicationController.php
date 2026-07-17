<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerApplicationController extends Controller
{
    public function create($id)
    {
        $event = Event::with('volunteerEvent')->findOrFail($id);
        
        if (!$event->volunteerEvent) {
            return redirect()->back()->with('error', 'This event is not a volunteer event.');
        }
        
        $participant_id = Auth::guard('participant')->id();
        $exists = VolunteerApplication::where('volunteer_event_id', $event->volunteerEvent->volunteer_event_id)
            ->where('participant_id', $participant_id)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($exists) {
            return redirect()->route('participant.events.show', $id)->with('error', 'You have already applied for this event.');
        }

        // Check capacity
        $approvedCount = $event->volunteerEvent->applications()->where('status', 'approved')->count();
        if ($event->volunteerEvent->capacity > 0 && $approvedCount >= $event->volunteerEvent->capacity) {
            return redirect()->route('participant.events.show', $id)->with('error', 'Sorry, this event is already full.');
        }

        return view('participant.events.apply', [
            'event' => $event
        ]);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $participant = Auth::guard('participant')->user();
        $participant->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $participant_id = $participant->participant_id;
        $event = Event::with('volunteerEvent')->findOrFail($id);

        // Check if already applied (active/pending/approved/rejected)
        $exists = VolunteerApplication::where('volunteer_event_id', $event->volunteerEvent->volunteer_event_id)
            ->where('participant_id', $participant_id)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($exists) {
            return redirect()->route('participant.events.show', $id)->with('error', 'You have already applied for this event.');
        }

        // Check capacity again to be safe
        $approvedCount = $event->volunteerEvent->applications()->where('status', 'approved')->count();
        if ($event->volunteerEvent->capacity > 0 && $approvedCount >= $event->volunteerEvent->capacity) {
            return redirect()->route('participant.events.show', $id)->with('error', 'Sorry, this event is already full.');
        }

        $application = VolunteerApplication::create([
            'volunteer_event_id' => $event->volunteerEvent->volunteer_event_id,
            'participant_id' => $participant_id,
            'skills' => $request->input('skills', []),
            'notes' => $request->input('notes'),
            'status' => 'pending'
        ]);

        // ─── Notify All Admins ───
        $participantName = Auth::guard('participant')->user()->name;
        Notification::notifyAllAdmins(
            'new_application',
            'New Volunteer Application',
            "{$participantName} has applied to volunteer for \"{$event->title}\". Review now.",
            'user-plus',
            route('admin.applications.index') . '?show_app=' . $application->application_id
        );

        return redirect()->route('participant.activities.index')->with('success', 'Application submitted successfully!');
    }
    public function edit($id)
    {
        $application = VolunteerApplication::where('participant_id', Auth::guard('participant')->id())
            ->where('application_id', $id)
            ->with('volunteerEvent.event')
            ->firstOrFail();

        // Optional: only allow editing if pending
        if ($application->status !== 'pending') {
            return redirect()->route('participant.activities.index')->with('error', 'You can only edit pending applications.');
        }

        return view('participant.events.edit_apply', compact('application'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $participant = Auth::guard('participant')->user();
        $participant->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $application = VolunteerApplication::where('participant_id', $participant->participant_id)
            ->where('application_id', $id)
            ->firstOrFail();

        if ($application->status !== 'pending') {
            return redirect()->route('participant.activities.index')->with('error', 'You can only edit pending applications.');
        }

        $application->update([
            'skills' => $request->input('skills', []),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('participant.activities.index')->with('success', 'Application updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $application = VolunteerApplication::where('participant_id', Auth::guard('participant')->id())
            ->where('application_id', $id)
            ->with('volunteerEvent.event')
            ->firstOrFail();

        // Allow cancellation if not already rejected
        if ($application->status === 'rejected' || $application->status === 'declined') {
            return redirect()->route('participant.activities.index')->with('error', 'You cannot cancel this application.');
        }

        $application->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->input('cancel_reason')
        ]);

        // ─── Notify All Admins ───
        $participantName = Auth::guard('participant')->user()->name;
        $eventName = $application->volunteerEvent->event->title ?? 'Unknown Event';
        
        $message = "{$participantName} has cancelled their volunteer application for \"{$eventName}\".";
        $message .= "\nReason: " . $application->cancel_reason;

        Notification::notifyAllAdmins(
            'application_cancelled',
            'Application Cancelled',
            $message,
            'user-x',
            route('admin.applications.index')
        );

        return redirect()->route('participant.activities.index')->with('success', 'Application cancelled successfully.');
    }
}
