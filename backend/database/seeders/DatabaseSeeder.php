<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AnimalSeeder::class, //si
            ProductosSeeder::class ,//si
            RazaSeeder::class,  //si
             RolSeeder::class, //si
             ServiciosSeeder::class, //si
           // UsuariosSeeder::class,
            UserSeeder::class,  //si
            TrabajadorSeeder::class,  //si
            ClienteSeeder::class,  //si
            HorarioSeeder::class, //si
            MascotaSeeder::class, //si
             HorarioTrabajadorSeeder::class, //si
            CitaSeeder::class,
            ConsultaSeeder::class,
          //  ProductosSeeder::class,
           
           // AdminSeeder::class,

            //HorariosSeeder::class,
 
        ]);
    }
}

