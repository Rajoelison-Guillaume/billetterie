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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('showtime_id')->nullable()->constrained('showtimes');
            $table->foreignId('seat_id')->nullable()->constrained('seats');
            $table->decimal('price',10,2);
            $table->string('qr_code')->unique();
            $table->string('status',50)->default('unused');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
