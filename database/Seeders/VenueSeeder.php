<?php
// VenueSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run()
    {
        Venue::create([
            'name' => 'Palais des Sports',
            'address' => 'Mahamasina',
            'city' => 'Antananarivo',
            'description' => 'Grande salle pour concerts et spectacles',
        ]);
    }
}
