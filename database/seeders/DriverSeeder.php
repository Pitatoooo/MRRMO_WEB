<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run()
    {
        Driver::create([
            'name' => 'Test Driver',
            'email' => 'driver@test.com',
            'password' => Hash::make('password123'), // 🔐
        ]);
    }
}