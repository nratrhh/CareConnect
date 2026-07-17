<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view (Participant only).
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request for Participants.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ic_number' => ['required', 'string', 'max:14', 'unique:participant,ic_number'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:participant,email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one number
            ],
        ], [
            'ic_number.unique' => 'This IC Number is already registered.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter and one number.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $participant = Participant::create([
            'name' => $request->name,
            'ic_number' => $request->ic_number,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Do NOT log them in automatically. Redirect back to register page with success message.
        return back()->with('status', 'Account successfully created! Please log in to continue.');
    }
}
