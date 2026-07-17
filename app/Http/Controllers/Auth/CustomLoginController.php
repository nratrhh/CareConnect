<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,participant',
        ]);

        $credentials = $request->only('email', 'password');
        $role = $request->role;

        // Validate that the email exists in the correct table for the selected role
        if ($role === 'admin') {
            $userExists = Admin::where('email', $request->email)->exists();
            if (!$userExists) {
                return back()->withErrors([
                    'email' => 'This email is not registered as an Admin account.',
                ])->withInput($request->except('password'));
            }
        } else {
            $userExists = Participant::where('email', $request->email)->exists();
            if (!$userExists) {
                return back()->withErrors([
                    'email' => 'This email is not registered as a Participant account.',
                ])->withInput($request->except('password'));
            }
        }

        // Attempt authentication with the appropriate guard
        if (Auth::guard($role)->attempt($credentials)) {
            $request->session()->regenerate();

            if ($role === 'admin') {
                return redirect(url('/admin/dashboard'));
            } else {
                return redirect(url('/participant/dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The password you entered is incorrect.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('participant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}