<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Support\Str;

class EfainaTestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0341234567',
            'password' => bcrypt('password'),
        ]);

        $order = Order::create([
            'user_id'      => $user->id,
            'status'       => 'pending',
            'total_amount' => 10000,
        ]);

        $ticket = Ticket::create([
            'order_id' => $order->id,
            'event_id' => 1, // mets un ID d’événement existant
            'price'    => 10000,
            'status'   => 'unused',
            'qr_code'  => Str::uuid()->toString(),
        ]);

        Payment::create([
            'order_id'     => $order->id,
            'amount'       => $order->total_amount,
            'method'       => 'mvola',
            'provider'     => 'MVola',
            'provider_ref' => 'sandbox-tx-12345',
            'status'       => 'pending',
        ]);
    }
}
