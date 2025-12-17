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
        Schema::create('session_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['available', 'reserved'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_seats');
    }
};
