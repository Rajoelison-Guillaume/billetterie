<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\Event::create([
        'organizer_id' => 1,
        'venue_id' => 1,
        'room_id' => 1,
        'event_type_id' => 1,
        'title' => 'Avant-première One Piece',
        'description' => 'Projection spéciale du film One Piece',
        'start_date' => '2025-12-10 18:00:00',
        'end_date' => '2025-12-10 21:00:00',
        'ticket_price' => 15000,
        'max_per_user' => 5,
        'is_active' => true,
        'category' => 'cinema',
    ]);
}

}
