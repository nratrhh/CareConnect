<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;

class NewPasswordController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function verifyOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        
        return view('auth.verify-otp');
    }

    /**
     * Handle the OTP verification request.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|digits:6']);
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        // Get the token record
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.'])->withInput();
        }

        // Mark OTP as verified in session
        session(['otp_verified' => true]);

        return redirect()->route('password.reset')->with('status', 'OTP verified. Please enter your new password.');
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (!session()->has('reset_email') || !session()->has('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => session('reset_email')]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!session()->has('reset_email') || !session()->has('otp_verified')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter and one number.',
        ]);

        $email = session('reset_email');

        // Update password manually
        $participant = Participant::where('email', $email)->first();
        
        if ($participant) {
            $participant->forceFill([
                'password' => Hash::make($request->password)
            ])->save();

            event(new PasswordReset($participant));
            
            // Delete token
            DB::table('password_reset_tokens')->where('email', $email)->delete();
        }

        // Clear session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }
}
