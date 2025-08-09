<?php
// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Robin',
            'email' => 'robinjo1776@gmail.com',
            'username' => 'robinjo1776',
            'password' => Hash::make('LIbras123456789!'),
        ]);
    }
}
