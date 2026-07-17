<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('event_id');
            $table->unsignedBigInteger('admin_id');
            
            $table->string('title');
            $table->string('eventImg')->nullable();
            $table->text('description');
            $table->date('date');
            $table->string('location');
            $table->string('status');

            $table->timestamps();

            $table->foreign('admin_id')
                ->references('admin_id')
                ->on('admins')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};