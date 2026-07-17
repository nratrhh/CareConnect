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
        Schema::create('volunteer_events', function (Blueprint $table) {
            $table->bigIncrements('volunteer_event_id');
            $table->unsignedBigInteger('event_id');

            $table->integer('capacity');

            $table->timestamps();

            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_events');
    }
};
