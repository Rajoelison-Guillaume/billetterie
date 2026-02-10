<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Table seats (créée seulement si elle n'existe pas déjà)
        if (!Schema::hasTable('seats')) {
            Schema::create('seats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->string('row_label', 10)->nullable();
                $table->string('seat_number', 10)->nullable();
                $table->boolean('is_accessible')->default(false);
                $table->timestamps();
            });
        }

        // Table reservation_seat (créée seulement si elle n'existe pas déjà)
        if (!Schema::hasTable('reservation_seat')) {
            Schema::create('reservation_seat', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
                $table->foreignId('seat_id')->constrained()->cascadeOnDelete();
                $table->foreignId('showtime_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('ticket_id')->nullable()->constrained()->cascadeOnDelete();
                $table->timestamp('reserved_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_seat');
        Schema::dropIfExists('seats');
    }
};
