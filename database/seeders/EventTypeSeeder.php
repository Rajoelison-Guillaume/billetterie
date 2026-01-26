<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    public function run()
    {
        EventType::firstOrCreate(
            ['code' => 'CIN'],
            ['label' => 'Cinéma']
        );

        EventType::firstOrCreate(
            ['code' => 'LIB'],
            ['label' => 'Événement libre']
        );
    }
}
