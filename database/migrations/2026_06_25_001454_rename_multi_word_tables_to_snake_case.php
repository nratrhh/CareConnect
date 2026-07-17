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
        Schema::rename('fundraisingcampaign', 'fundraising_campaign');
        Schema::rename('volunteerevent', 'volunteer_event');
        Schema::rename('volunteerapplication', 'volunteer_application');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('fundraising_campaign', 'fundraisingcampaign');
        Schema::rename('volunteer_event', 'volunteerevent');
        Schema::rename('volunteer_application', 'volunteerapplication');
    }
};
