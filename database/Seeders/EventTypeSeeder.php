<?php
// EventTypeSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    public function run()
    {
        EventType::create([
            'code' => 'CIN',
            'label' => 'Cinéma',
        ]);

        EventType::create([
            'code' => 'LIB',
            'label' => 'Événement libre',
        ]);
    }
}
