<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::firstOrCreate([
            'nombre'   => 'Juan Pérez',
            'telefono' => '8121234561',
            'email'    => 'juan@gmail.com',
            'password' => bcrypt('12345678'),
            'rol_id'   => 2,
            'estado'   => 'pendiente',
        ]);
        DB::table('trabajadores')->insert([
            'user_id' => $user1->id, 'created_at' => now(), 'updated_at' => now()
        ]);

        $user2 = User::firstOrCreate([
            'nombre'   => 'María García',
            'telefono' => '8121234562',
            'email'    => 'maria@gmail.com',
            'password' => bcrypt('12345678'),
            'rol_id'   => 2,
            'estado'   => 'pendiente',
        ]);
        DB::table('trabajadores')->insert([
            'user_id' => $user2->id, 'created_at' => now(), 'updated_at' => now()
        ]);

        $user3 = User::firstOrCreate([
            'nombre'   => 'Carlos López',
            'telefono' => '8121234563',
            'email'    => 'carlos@gmail.com',
            'password' => bcrypt('12345678'),
            'rol_id'   => 2,
            'estado'   => 'pendiente',
        ]);
        DB::table('trabajadores')->insert([
            'user_id' => $user3->id, 'created_at' => now(), 'updated_at' => now()
        ]);
    }
}