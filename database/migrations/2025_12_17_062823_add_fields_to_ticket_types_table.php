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
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->string('name'); // ex: VIP, Standard
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(0); // stock disponible
            $table->unsignedBigInteger('event_id'); // pour rattacher à un événement

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            //
        });
    }
};
