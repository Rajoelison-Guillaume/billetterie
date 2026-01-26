<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run()
    {
        Event::firstOrCreate([
            'title' => 'Avant-première One Piece',
        ], [
            'organizer_id' => 1,
            'venue_id' => 1,
            'room_id' => 1,
            'event_type_id' => 1,
            'slug' => 'avant-premiere-one-piece', 
            'description' => 'Projection spéciale du film One Piece',
            'start_date' => '2025-12-10 18:00:00',
            'end_date' => '2025-12-10 21:00:00',
            'ticket_price' => 15000,
            'is_active' => true,
            'category' => 'cinema',
        ]);
    }
}
