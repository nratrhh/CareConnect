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
        Schema::create('fundraising_campaigns', function (Blueprint $table) {
            $table->bigIncrements('fundraising_campaign_id');
            $table->unsignedBigInteger('event_id');

            $table->decimal('target_amount', 10, 2);
            $table->decimal('collected_amount', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');

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
        Schema::dropIfExists('fundraising_campaigns');
    }
};
