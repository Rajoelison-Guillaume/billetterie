<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin ',
            'email' => 'admin@billetterie.mg',
            'phone' => '+261340000000',
            'role' => 'admin',
            'password' => Hash::make('admin1234'),
        ]);
    }
}
