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
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->bigIncrements('application_id');
            $table->unsignedBigInteger('volunteer_event_id');
            $table->unsignedBigInteger('participant_id');

            $table->string('status')->default('pending');
            $table->timestamp('applied_at')->useCurrent();

            $table->timestamps();

            $table->foreign('volunteer_event_id')
                ->references('volunteer_event_id')
                ->on('volunteer_events')
                ->onDelete('cascade');

            $table->foreign('participant_id')
                ->references('participant_id')
                ->on('participants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
