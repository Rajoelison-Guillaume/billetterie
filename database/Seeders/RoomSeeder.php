<?php
// RoomSeeder.php
namespace Database\Seeders;
use Illuminate\database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'venue_id' => 1,
            'name' => 'Salle principale',
            'capacity' => 500,
            'description' => 'Salle de projection cinéma',
        ]);
    }
}
