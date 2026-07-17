<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin Notifications (for Admin ID: 1) ───
        Notification::create([
            'user_type'  => 'admin',
            'user_id'    => 1,
            'type'       => 'new_application',
            'title'      => 'New Volunteer Application',
            'message'    => 'Amar Syauqi Bin Yasin has applied to volunteer for "Sea Turtle Conservation". Review now.',
            'icon'       => 'user-plus',
            'action_url' => '/admin/applications',
        ]);

        Notification::create([
            'user_type'  => 'admin',
            'user_id'    => 1,
            'type'       => 'new_donation',
            'title'      => 'New Donation Received',
            'message'    => 'Noor Athirah donated RM 150.00 to "School Aid Fund for Asnaf Students".',
            'icon'       => 'dollar-sign',
            'action_url' => '/admin/fundraising',
        ]);

        Notification::create([
            'user_type'  => 'admin',
            'user_id'    => 1,
            'type'       => 'application_cancelled',
            'title'      => 'Application Cancelled',
            'message'    => 'Megat Faris Putra has cancelled their volunteer application for "Kasih Warga Emas".',
            'icon'       => 'user-x',
            'action_url' => '/admin/applications',
        ]);

        // ─── Participant Notifications (for Participant ID: 2 - Amar Syauqi) ───
        Notification::create([
            'user_type'  => 'participant',
            'user_id'    => 2,
            'type'       => 'application_approved',
            'title'      => 'Application Approved!',
            'message'    => 'Congratulations! Your volunteer application for "Sea Turtle Conservation" has been approved.',
            'icon'       => 'check-circle',
            'action_url' => '/participant/my-activities',
        ]);

        Notification::create([
            'user_type'  => 'participant',
            'user_id'    => 2,
            'type'       => 'new_event',
            'title'      => 'New Volunteer Event!',
            'message'    => 'A new Volunteer Event "Kasih Warga Emas" is now open. Check it out!',
            'icon'       => 'users',
            'action_url' => '/participant/events/1',
        ]);

        Notification::create([
            'user_type'  => 'participant',
            'user_id'    => 2,
            'type'       => 'donation_success',
            'title'      => 'Donation Successful!',
            'message'    => 'Thank you! Your donation of RM 100.00 for "School Aid Fund" has been received.',
            'icon'       => 'heart',
            'action_url' => '/participant/my-activities',
        ]);

        Notification::create([
            'user_type'  => 'participant',
            'user_id'    => 2,
            'type'       => 'certificate_available',
            'title'      => 'E-Certificate Available!',
            'message'    => 'Your e-certificate for "Sea Turtle Conservation" is now ready. View it in My Activities.',
            'icon'       => 'award',
            'action_url' => '/participant/my-activities',
        ]);
    }
}
