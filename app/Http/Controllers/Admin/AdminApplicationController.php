<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = VolunteerApplication::with([
                'participant',
                'volunteerEvent.event'
            ])
            ->orderBy('applied_at', 'desc')
            ->get();

        $activeEvents = \App\Models\VolunteerEvent::whereHas('event', function($q) {
                $q->where('status', 'active');
            })
            ->with(['event', 'applications' => function($q) {
                $q->where('status', 'approved');
            }])
            ->get();

        return view('admin.applications', compact('applications', 'activeEvents'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,declined,cancelled',
            'decline_reason' => 'nullable|string|max:500',
        ]);

        $application = VolunteerApplication::with('volunteerEvent.event')->findOrFail($id);
        
        $updateData = ['status' => $request->status];
        if ($request->status === 'declined') {
            $updateData['decline_reason'] = $request->input('decline_reason');
        } else {
            $updateData['decline_reason'] = null; // Clear if not declined
        }
        $application->update($updateData);

        $statusLabel = ucfirst($request->status);
        $eventName = $application->volunteerEvent->event->title ?? 'Unknown Event';

        // ─── Send Notification to Participant ───
        if (in_array($request->status, ['approved', 'declined'])) {
            $icon = $request->status === 'approved' ? 'check-circle' : 'x-circle';
            $title = $request->status === 'approved'
                ? 'Application Approved!'
                : 'Application Declined';
            $message = $request->status === 'approved'
                ? "Congratulations! Your volunteer application for \"{$eventName}\" has been approved."
                : "Your volunteer application for \"{$eventName}\" has been declined.";
            
            if ($request->status === 'declined' && $application->decline_reason) {
                $message .= " Reason: " . $application->decline_reason;
            }

            Notification::notifyParticipant(
                $application->participant_id,
                'application_' . $request->status,
                $title,
                $message,
                $icon,
                route('participant.activities.index') . '?show_app=' . $application->application_id
            );
        }

        return redirect()->route('admin.applications.index')
            ->with('success', "Application {$statusLabel} successfully.");
    }

    public function show($id)
    {
        $application = VolunteerApplication::with(['participant', 'volunteerEvent.event'])->findOrFail($id);
        
        if (request()->ajax()) {
            $application->append('formatted_id');
            return response()->json($application);
        }
        
        return view('admin.application_show', compact('application'));
    }
}
