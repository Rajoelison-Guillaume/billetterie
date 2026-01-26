<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Showtime;

class ShowtimeSeeder extends Seeder
{
    public function run()
    {
        $events = Event::where('category', 'cinema')->get();

        foreach ($events as $event) {
            Showtime::firstOrCreate([
                'event_id' => $event->id,
                'room_id'  => $event->room_id,
                'start_at' => $event->start_date,
            ], [
                'end_at'   => $event->end_date,
            ]);
        }
    }
}
