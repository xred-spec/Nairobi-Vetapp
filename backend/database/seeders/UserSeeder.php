<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $admins = [
            ['email' => 'admin@example.com', 'nombre' => 'Admin General'],
            ['email' => 'fandepxndx2012@yahoo.com', 'nombre' => 'Astorga'],
            ['email' => 'emomoreno@hotmail.com', 'nombre' => 'Cesar'],
            ['email' => 'ernesto666tuntunsahur@hotmail.com', 'nombre' => 'Ernesto'],
            ['email' => 'erizo@hotmail.com', 'nombre' => 'Mario'],
            ['email' => 'alejandro@hotmail.com', 'nombre' => 'Alejandro'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'email' => $admin['email'],
                'nombre' => $admin['nombre'],
                'telefono' => '111111' . rand(1000, 9999),
                'password' => Hash::make('123456789'),
                'rol_id' => 3, // Admin
                'estado' => 'registrado',
                'email_verified_at' => now()
            ]);
        }

        $staffNames = [
            'Juan Pérez', 'Dra. Elena Méndez', 'Dr. Carlos Ruiz', 
            'Dra. Sofía Luna', 'Luis Alberto Gómez', 'Dra. Martha Soto'
        ];

        foreach ($staffNames as $index => $name) {
            User::create([
                'email' => str_replace(" ","_",$name) . '@nairobi.com',
                'nombre' => $name,
                'telefono' => '552222000' . $index,
                'password' => Hash::make('123456789'),
                'rol_id' => 2, // Trabajador
                'estado' => 'registrado',
                'email_verified_at' => now()
            ]);
        }

        $nombres = [
            'Ana','José','Luis','María','Carlos','Laura','Jorge','Carmen','Miguel','Sofía',
            'Diego','Lucía','Fernando','Elena','Ricardo','Patricia','Raúl','Gabriela','Hugo','Daniel',
            'Paola','Javier','Andrea','Sergio','Valeria','Eduardo','Mónica','Alejandro','Diana','Roberto',
            'Claudia','Iván','Natalia','Óscar','Verónica','Arturo','Beatriz','Tomás','Lidia','Emilio',
            'Brenda','Francisco','Cecilia','Marco','Rosa','Ignacio','Pilar','Adrián','Marisol','Esteban',
            'Gloria','René','Fabiola','Saúl','Karina','Armando','Ximena','Rubén','Camila','Cristian',
            'Daniela','Víctor','Paula','Andrés','Silvia','Mateo','Erika','Julio','Noemí','Mauricio',
            'Yolanda','Gerardo','Alicia','Leonardo','Nadia','Alonso','Miriam','Ramiro','Leticia','Guillermo',
            'Berenice','Samuel','Rocío','Efraín','Yesenia','Orlando','Gisela','Héctor','Dulce','Ismael'
        ];

        $apellidos = [
            'García','Martínez','Hernández','López','Sánchez','González','Pérez','Rodríguez','Ramírez','Torres',
            'Flores','Rivera','Gómez','Díaz','Cruz','Morales','Ortiz','Reyes','Castro','Vargas',
            'Navarro','Molina','Delgado','Romero','Ruiz','Campos','Herrera','Medina','Vega','Silva',
            'Rojas','Guerrero','Mendoza','Carrillo','Peña','Cabrera','Acosta','Salazar','Paredes','Solís',
            'Ibarra','Luna','Duarte','Jiménez','Valdez','Cárdenas','Escobar','Fuentes','Bravo','Tapia',
            'Zamora','Espinoza','Aguirre','Cervantes','Bautista','Montoya','Treviño','Villarreal','Lozano','Ochoa',
            'Santillán','Leal','Galván','Amaya','Benítez','Rosales','Peralta','Figueroa','Barrera','Arriaga',
            'Zúñiga','Meza','Quintana','Rocha','Huerta','Téllez','Villanueva','Cisneros','Mena','Rentería',
            'Padilla','Castañeda','Godoy','Vázquez','Lomelí','Murillo','Ponce','Esquivel','Arellano','Trejo'
        ];
        $domains = [
            'gmail.com',
            'yahoo.com',
            'hotmail.com',
            'outlook.com',
            'icloud.com',
            'protonmail.com',
            'live.com',
            'msn.com',
            'aol.com',
            'mail.com',
        ];
        for ($i=0;$i<400;$i++) {
            $name = $nombres[array_rand($nombres)].' '.$apellidos[array_rand($apellidos)].' '.$apellidos[array_rand($apellidos)];
            $created_at = now()->subDays(rand(1, 180));
            if ($created_at->diffInDays(now()) <= 2) {
                $email_verified_at = null;
            } else {
                $maxDays = $created_at->diffInDays(now()); // días disponibles hasta hoy
                $email_verified_at = $created_at->copy()->addDays(rand(0, $maxDays));
            }

            User::create([
                'email' => str_replace(" ","_",$name) . '@' . $domains[array_rand($domains)],
                'nombre' => $name,
                'telefono' => '55' . rand(10000000, 99999999),
                'password' => Hash::make('123456789'),
                'rol_id' => 1,
                'estado' => 'registrado',
                'email_verified_at' => $email_verified_at,
                'created_at' =>$created_at
            ]);
        }
    }
}