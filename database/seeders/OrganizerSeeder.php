<?php
// OrganizerSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Organizer;

class OrganizerSeeder extends Seeder
{
    public function run()
    {
        Organizer::create([
            'name' => 'Canal 7 Events',
            'contact_email' => 'contact@canal7.mg',
            'contact_phone' => '+261340000000',
            'description' => 'Agence événementielle malgache',
        ]);
    }
}
