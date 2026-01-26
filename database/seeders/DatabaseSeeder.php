<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\SeatSeeder;
use Database\Seeders\ShowtimeSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizerSeeder::class,
            UserSeeder::class,
            VenueSeeder::class,
            RoomSeeder::class,
            EventTypeSeeder::class,
            EventSeeder::class,
            SeatSeeder::class, 
            ShowtimeSeeder::class, 
        ]);
    }
}

