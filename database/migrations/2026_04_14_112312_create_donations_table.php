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
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('donation_id');
            $table->unsignedBigInteger('fundraising_campaign_id');
            $table->unsignedBigInteger('participant_id');

            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->timestamp('donation_date')->useCurrent();

            $table->timestamps();

            $table->foreign('fundraising_campaign_id')
                ->references('fundraising_campaign_id')
                ->on('fundraising_campaigns')
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
        Schema::dropIfExists('donations');
    }
};
