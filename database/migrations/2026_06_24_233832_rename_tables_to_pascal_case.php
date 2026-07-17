<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('admins', 'Admin');
        Schema::rename('participants', 'Participant');
        Schema::rename('events', 'Event');
        Schema::rename('fundraising_campaigns', 'FundraisingCampaign');
        Schema::rename('volunteer_events', 'VolunteerEvent');
        Schema::rename('donations', 'Donation');
        Schema::rename('volunteer_applications', 'VolunteerApplication');
        Schema::rename('notifications', 'Notification');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('Admin', 'admins');
        Schema::rename('Participant', 'participants');
        Schema::rename('Event', 'events');
        Schema::rename('FundraisingCampaign', 'fundraising_campaigns');
        Schema::rename('VolunteerEvent', 'volunteer_events');
        Schema::rename('Donation', 'donations');
        Schema::rename('VolunteerApplication', 'volunteer_applications');
        Schema::rename('Notification', 'notifications');
    }
};
