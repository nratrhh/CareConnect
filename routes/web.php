<?php

use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminFundraisingController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $thirtyDaysFromNow = now()->addDays(30);
    $activeEvents = \App\Models\Event::select('event.*')
        ->leftJoin('volunteer_event', 'event.event_id', '=', 'volunteer_event.event_id')
        ->leftJoin('fundraising_campaign', 'event.event_id', '=', 'fundraising_campaign.event_id')
        ->where('event.status', 'active')
        ->where(function($q) use ($thirtyDaysFromNow) {
            $q->where(function($sq) use ($thirtyDaysFromNow) {
                $sq->where('volunteer_event.eventDate', '>=', now()->startOfDay())
                   ->where('volunteer_event.eventDate', '<=', $thirtyDaysFromNow);
            })
            ->orWhere(function($sq) use ($thirtyDaysFromNow) {
                $sq->where('fundraising_campaign.end_date', '>=', now()->startOfDay())
                   ->where('fundraising_campaign.start_date', '<=', $thirtyDaysFromNow);
            });
        })
        ->with(['volunteerEvent.applications', 'fundraisingCampaign.donations'])
        ->orderByRaw('COALESCE(volunteer_event.eventDate, fundraising_campaign.end_date) ASC')
        ->get();

    return view('welcome', ['activeEvents' => $activeEvents]);
});

// ─── Guest Routes (unauthenticated users only) ───
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomLoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomLoginController::class, 'login']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/verify-otp', [NewPasswordController::class, 'verifyOtpForm'])->name('password.verify_otp');
    Route::post('/verify-otp', [NewPasswordController::class, 'verifyOtp']);
    Route::get('/reset-password', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

// ─── Admin Routes ───
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Events
    Route::get('/events', [AdminEventController::class, 'index'])->name('admin.events.index');
    Route::post('/events', [AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/events/{id}', [AdminEventController::class, 'show'])->name('admin.events.show');
    Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');

    // Applications
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/applications/{id}', [AdminApplicationController::class, 'show'])->name('admin.applications.show');
    Route::patch('/applications/{id}/status', [AdminApplicationController::class, 'updateStatus'])->name('admin.applications.updateStatus');

    // Fundraising
    Route::get('/fundraising', [AdminFundraisingController::class, 'index'])->name('admin.fundraising.index');

    // Admin view receipt for a donation
    Route::get('/fundraising/{donationId}/receipt', [AdminFundraisingController::class, 'receipt'])
        ->name('admin.fundraising.receipt');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/download', [AdminReportController::class, 'download'])->name('admin.reports.download');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::patch('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.readAll');

    // Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
});

// ─── Participant Routes ───
Route::middleware('auth:participant')->prefix('participant')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Participant\ParticipantDashboardController::class, 'index'])->name('participant.dashboard');
    
    // Events
    Route::get('/events', [\App\Http\Controllers\Participant\ParticipantEventController::class, 'index'])->name('participant.events.index');
    Route::get('/events/{id}', [\App\Http\Controllers\Participant\ParticipantEventController::class, 'show'])->name('participant.events.show');
    
    // Applications & Donations
    Route::get('/events/{id}/apply', [\App\Http\Controllers\Participant\VolunteerApplicationController::class, 'create'])->name('participant.events.apply');
    Route::post('/events/{id}/apply', [\App\Http\Controllers\Participant\VolunteerApplicationController::class, 'store'])->name('participant.events.apply.store');
    
    // Manage Applications
    Route::get('/applications/{id}/edit', [\App\Http\Controllers\Participant\VolunteerApplicationController::class, 'edit'])->name('participant.applications.edit');
    Route::put('/applications/{id}', [\App\Http\Controllers\Participant\VolunteerApplicationController::class, 'update'])->name('participant.applications.update');
    Route::delete('/applications/{id}', [\App\Http\Controllers\Participant\VolunteerApplicationController::class, 'destroy'])->name('participant.applications.destroy');
    
    Route::get('/events/{id}/donate', [\App\Http\Controllers\Participant\DonationController::class, 'create'])->name('participant.events.donate');
    Route::post('/events/{id}/donate', [\App\Http\Controllers\Participant\DonationController::class, 'store'])->name('participant.events.donate.store');
    Route::post('/events/create-payment-intent', [\App\Http\Controllers\Participant\DonationController::class, 'createPaymentIntent'])->name('participant.events.donate.intent');
    Route::get('/events/donate/simulation/{donation_id}', [\App\Http\Controllers\Participant\DonationController::class, 'simulation'])->name('participant.events.donate.simulation');
    Route::get('/events/donate/success/{donation_id}', [\App\Http\Controllers\Participant\DonationController::class, 'success'])->name('participant.events.donate.success');
    
    // Billplz FPX Routes
    Route::post('/events/{id}/donate-fpx', [\App\Http\Controllers\Participant\DonationController::class, 'donateFPX'])->name('participant.events.donate.fpx');
    Route::get('/fpx/return', [\App\Http\Controllers\Participant\DonationController::class, 'fpxReturn'])->name('participant.fpx.return');

    // My Activities
    Route::get('/my-activities', [\App\Http\Controllers\Participant\ParticipantActivityController::class, 'index'])->name('participant.activities.index');
    Route::get('/my-activities/receipt/{id}', [\App\Http\Controllers\Participant\ParticipantActivityController::class, 'receipt'])->name('participant.activities.receipt');
    Route::get('/my-activities/certificate/{id}', [\App\Http\Controllers\Participant\ParticipantActivityController::class, 'certificate'])->name('participant.activities.certificate');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Participant\ParticipantNotificationController::class, 'index'])->name('participant.notifications.index');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\Participant\ParticipantNotificationController::class, 'markAsRead'])->name('participant.notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Participant\ParticipantNotificationController::class, 'markAllAsRead'])->name('participant.notifications.readAll');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Participant\ParticipantProfileController::class, 'index'])->name('participant.profile');
    Route::put('/profile', [\App\Http\Controllers\Participant\ParticipantProfileController::class, 'update'])->name('participant.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Participant\ParticipantProfileController::class, 'updatePassword'])->name('participant.profile.password');
});

// ─── Billplz FPX Callback (server-to-server, no auth required) ───
Route::post('/billplz/callback', [\App\Http\Controllers\Participant\DonationController::class, 'fpxCallback'])->name('participant.fpx.callback');