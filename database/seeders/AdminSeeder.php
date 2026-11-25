<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'amerallawy764@gmail.com'],
            [
                'name' => 'Amer',
                'password' => Hash::make('Amer al 9@'),
                'role' => 'admin',
            ]
        );
    }
}


