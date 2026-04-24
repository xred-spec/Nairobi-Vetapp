<?php

namespace Database\Seeders;

use App\Models\HorarioTrabajador;
use App\Models\Trabajador;
use App\Models\Horario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HorarioTrabajadorSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        HorarioTrabajador::truncate();
        Schema::enableForeignKeyConstraints();

        $trabajadoresIds = Trabajador::pluck('id')->toArray();
        $horariosIds = Horario::pluck('id')->toArray();

        if (empty($trabajadoresIds) || empty($horariosIds)) {
            $this->command->error('Error: No hay trabajadores o horarios cargados.');
            return;
        }

        foreach ($trabajadoresIds as $trabajadorId) {
            $horariosAleatorios = array_rand(array_flip($horariosIds), rand(10, 20));

            foreach ((array)$horariosAleatorios as $horarioId) {
                HorarioTrabajador::create([
                    'trabajador_id' => $trabajadorId,
                    'horario_id' => $horarioId,
                    'asignado' => 'asignado'
                ]);
            }
        }

        $this->command->info('Horarios asignados a los trabajadores exitosamente.');
    }
}