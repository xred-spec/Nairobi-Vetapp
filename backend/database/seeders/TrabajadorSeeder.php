<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class TrabajadorSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Trabajador::truncate();
        Schema::enableForeignKeyConstraints();

        $trabajadoresUsers = User::where('rol_id', 2)->get();

        foreach ($trabajadoresUsers as $user) {
            Trabajador::create([
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}