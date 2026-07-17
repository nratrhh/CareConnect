<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if email exists in participants table
        $exists = Participant::where('email', $request->email)->exists();

        if (!$exists) {
            return back()->withErrors([
                'email' => 'We could not find an account with that email address.',
            ])->withInput();
        }

        // Generate a 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        // Delete any existing OTPs for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store the hashed OTP in the database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($otp),
            'created_at' => now()
        ]);

        // Send the OTP via email
        Mail::to($request->email)->send(new SendOtpMail($otp));

        // Store the email in session for the next step and redirect to verify OTP page
        session(['reset_email' => $request->email]);

        return redirect()->route('password.verify_otp')
                         ->with('status', 'We have emailed your 6-digit password reset code.');
    }
}
