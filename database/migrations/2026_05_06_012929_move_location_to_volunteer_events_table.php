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
        // 1. Add columns to volunteer_events
        Schema::table('volunteer_events', function (Blueprint $table) {
            $table->string('location')->nullable()->after('eventDate');
            $table->text('location_details')->nullable()->after('location');
        });

        // 2. Copy data
        \Illuminate\Support\Facades\DB::statement('UPDATE volunteer_events SET location = (SELECT location FROM events WHERE events.event_id = volunteer_events.event_id), location_details = (SELECT location_details FROM events WHERE events.event_id = volunteer_events.event_id)');

        // 3. Drop columns from events
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['location', 'location_details']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add columns back to events
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->text('location_details')->nullable();
        });

        // 2. Copy data back
        \Illuminate\Support\Facades\DB::statement('UPDATE events SET location = (SELECT location FROM volunteer_events WHERE volunteer_events.event_id = events.event_id), location_details = (SELECT location_details FROM volunteer_events WHERE volunteer_events.event_id = events.event_id)');

        // 3. Drop columns from volunteer_events
        Schema::table('volunteer_events', function (Blueprint $table) {
            $table->dropColumn(['location', 'location_details']);
        });
    }
};
