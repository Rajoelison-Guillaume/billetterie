<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /* =========================
        USERS (3NF OK)
        ========================= */
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
        });

        /* =========================
        EVENTS (3NF IMPROVED)
        ========================= */
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'organizer_id')) {
                $table->foreignId('organizer_id')->nullable()->constrained('organizers');
            }
            if (!Schema::hasColumn('events', 'venue_id')) {
                $table->foreignId('venue_id')->nullable()->constrained('venues');
            }
            if (!Schema::hasColumn('events', 'event_type_id')) {
                $table->foreignId('event_type_id')->nullable()->constrained('event_types');
            }
            if (!Schema::hasColumn('events', 'category')) {
                $table->string('category')->default('libre');
            }
        });

        /* =========================
        ORDERS (3NF OK)
        ========================= */
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });

        /* =========================
        PAYMENTS (3NF OK)
        ========================= */
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'provider')) {
                $table->string('provider')->nullable();
            }
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending');
            }
        });

        /* =========================
        TICKETS (CENTRAL ENTITY 3NF)
        ========================= */
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->string('status')->default('unused');
            }
        });

        /* =========================
        RESERVATIONS (OPTIONNEL → NORMALISATION)
        ========================= */
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'status')) {
                $table->string('status')->default('confirmed');
            }

            // ⚠️ IMPORTANT : éviter duplication avec orders
            if (!Schema::hasColumn('reservations', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained('orders');
            }
        });

        /* =========================
        SEATS / SESSION
        ========================= */
        Schema::table('session_seats', function (Blueprint $table) {
            if (!Schema::hasColumn('session_seats', 'status')) {
                $table->string('status')->default('available');
            }
        });
    }

    public function down(): void {}
};