<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ParticipantProfileController extends Controller
{
    public function index()
    {
        $participant = Auth::guard('participant')->user();
        return view('participant.profile', compact('participant'));
    }

    public function update(Request $request)
    {
        $participant = Auth::guard('participant')->user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $participant->update($data);

        return redirect()->route('participant.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8|regex:/[A-Z]/|regex:/[0-9]/',
        ], [
            'password.min'   => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter and one number.',
        ]);

        $participant = Auth::guard('participant')->user();

        if (!Hash::check($request->current_password, $participant->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $participant->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('participant.profile')
            ->with('success', 'Password changed successfully.');
    }
}
