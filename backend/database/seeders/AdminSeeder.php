<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nombre'   => 'Admin Principal',
                'telefono' => '8120000000',
                'password' => Hash::make('password123'),
                'rol_id'   => 3,
                'estado'   => 'registrado',
            ]
        );
    }
}