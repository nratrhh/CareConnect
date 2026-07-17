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
        // Add eventDate to volunteer_events
        Schema::table('volunteer_events', function (Blueprint $table) {
            $table->date('eventDate')->nullable()->after('capacity');
        });

        // Copy data from events table
        \Illuminate\Support\Facades\DB::statement('UPDATE volunteer_events SET eventDate = (SELECT date FROM events WHERE events.event_id = volunteer_events.event_id)');

        // Drop date from events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }

    public function down(): void
    {
        // Re-add date to events
        Schema::table('events', function (Blueprint $table) {
            $table->date('date')->nullable()->after('description');
        });

        // Copy data back
        \Illuminate\Support\Facades\DB::statement('UPDATE events SET date = (SELECT eventDate FROM volunteer_events WHERE volunteer_events.event_id = events.event_id)');

        // Drop eventDate from volunteer_events
        Schema::table('volunteer_events', function (Blueprint $table) {
            $table->dropColumn('eventDate');
        });
    }
};
