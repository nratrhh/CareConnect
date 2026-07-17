<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->string('user_type');          // 'admin' or 'participant'
            $table->unsignedBigInteger('user_id'); // admin_id or participant_id
            $table->string('type');                // e.g. 'application_approved', 'new_event', 'new_donation'
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();    // icon identifier
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_type', 'user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
