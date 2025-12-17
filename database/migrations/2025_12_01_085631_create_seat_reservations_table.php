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
        Schema::create('seat_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained('showtimes');
            $table->foreignId('seat_id')->constrained('seats');
            $table->foreignId('ticket_id')->nullable()->constrained('tickets');
            $table->timestamp('reserved_at')->useCurrent();
            $table->timestamps();
            $table->unique(['showtime_id','seat_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_reservations');
    }
};
