<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Cliente::truncate();
        Schema::enableForeignKeyConstraints();

        $municipios = ['Torreon','Gomez palacio','Lerdo','Matamoros','Francisco I. Madero'];
        $colonias = ['Centro', 'Roma', 'Condesa', 'Polanco', 'Narvarte', 'Coyoacán', 'Iztapalapa', 'Gustavo A. Madero', 'Benito Juárez', 'Miguel Hidalgo'];
        
        $coloniasTorreon = [
            'Centro','Nueva Los Ángeles','Ampliación Los Ángeles','San Isidro','Roma','Navarro','El Fresno','Torreón Jardín','Campestre La Rosita','Rincón La Merced',
            'La Merced','Valle Oriente','Quintas San Isidro','Los Viñedos','Monte Real','Sol de Oriente','Joyas del Bosque','Las Etnias','Villa Florida','Residencial Senderos',
            'Las Torres','Hacienda El Rosario','Las Julietas','Los Ángeles','La Fuente','Villa Jacarandas','Las Margaritas','Las Quintas','Hogar Moderno','Primero de Mayo',
            'Vicente Guerrero','Luis Echeverría','Ex Hacienda La Merced','La Dalia','La Mina','Las Brisas','Fovissste La Rosita','San Joaquín','La Noria','Santa María',
            'El Tajito','Ciudad Nazas','Joyas del Oriente','Villa California','Las Alamedas','Las Misiones','Los Cedros','Villas Universidad','Puerta Real','Ampliación La Rosita'
        ];

        $coloniasGomezPalacio = [
            'Centro','Filadelfia','Santa Rosa','El Dorado','Las Rosas','Chapala','Tierra Blanca','Casa Blanca','Héctor Mayagoitia','Ampliación Santa Rosa',
            'Las Huertas','Nuevo Los Álamos','El Refugio','Campestre Gómez Palacio','La Feria','Bellavista','Revolución','5 de Mayo','Miguel de la Madrid','Valle del Nazas',
            'Villa Nápoles','Los Álamos','El Amigo','Solidaridad','Santa Teresa','Ampliación Bellavista','Parque Hundido','Las Torres','El Consuelo','San Antonio',
            'Los Laureles','Ampliación Miguel de la Madrid','Santa Sofía','El Mezquital','Las Palmas','Las Granjas','Francisco Zarco','Torreón Residencial','Las Brisas','Rincón San Antonio',
            'Valle Azul','Nuevo Gómez','Santa Elena','Las Villas','Ampliación El Dorado','Los Pinos','Villa Campestre','Santa Lucía','Campestre Real','Las Fuentes'
        ];

        $coloniasGomezPalacio = [
            'Centro','Filadelfia','Santa Rosa','El Dorado','Las Rosas','Chapala','Tierra Blanca','Casa Blanca','Héctor Mayagoitia','Ampliación Santa Rosa',
            'Las Huertas','Nuevo Los Álamos','El Refugio','Campestre Gómez Palacio','La Feria','Bellavista','Revolución','5 de Mayo','Miguel de la Madrid','Valle del Nazas',
            'Villa Nápoles','Los Álamos','El Amigo','Solidaridad','Santa Teresa','Ampliación Bellavista','Parque Hundido','Las Torres','El Consuelo','San Antonio',
            'Los Laureles','Ampliación Miguel de la Madrid','Santa Sofía','El Mezquital','Las Palmas','Las Granjas','Francisco Zarco','Torreón Residencial','Las Brisas','Rincón San Antonio',
            'Valle Azul','Nuevo Gómez','Santa Elena','Las Villas','Ampliación El Dorado','Los Pinos','Villa Campestre','Santa Lucía','Campestre Real','Las Fuentes'
        ];

        $coloniasMatamoros = [
            'Centro','Las Brisas','Las Flores','La Amistad','Ejidal','Las Palmas','San Miguel','La Libertad','Independencia','La Esperanza',
            'Las Vegas','San José','Nuevo Matamoros','Las Huertas','El Consuelo','Las Torres','Ampliación Centro','La Rosita','La Loma','El Refugio',
            'Las Quintas','San Antonio','El Progreso','Las Delicias','La Joya','El Paraíso','Las Palmas II','San Isidro','La Merced','El Ranchito',
            'Las Misiones','San Pedro','El Oasis','La Fuente','Las Lomas','Valle Verde','La Providencia','San Carlos','El Mezquital','La Esperanza II',
            'Las Rosas','San Felipe','La Unión','Las Granjas','El Edén','La Dalia','San Juan','La Victoria','Las Palmas III','El Milagro'
        ];

        $coloniasMadero = [
            'Centro','Las Vegas','Nuevo Jaboncillo','Jaboncillo','Las Flores','La Esperanza','San José','La Libertad','Independencia','El Refugio',
            'Las Palmas','La Amistad','El Progreso','San Antonio','La Joya','Las Brisas','El Oasis','San Isidro','La Loma','Las Quintas',
            'El Paraíso','San Juan','La Merced','Las Delicias','La Fuente','El Consuelo','Las Torres','San Pedro','La Providencia','Las Rosas',
            'El Edén','La Unión','San Miguel','Las Granjas','El Mezquital','La Victoria','San Felipe','La Dalia','Las Palmas II','El Milagro',
            'San Carlos','La Esperanza II','Las Lomas','Valle Verde','La Hacienda','El Ranchito','Las Misiones','San Ignacio','La Paz','El Carmen'
        ];

        
        $fechaEspecial = now()->subDays(rand(100, 180));
       /* Cliente::create([
            'municipio' => 'Ciudad de México',
            'colonia' => 'Centro',
            'calle' => 'Av. Reforma',
            'numero_exterior' => '123',
            'user_id' => 4,
            'codigo_postal' => '06000',
            'created_at' => $fechaEspecial,
            'updated_at' => $fechaEspecial
        ]);*/
        $usuarios = User::where('rol_id',1)->get();

        foreach ($usuarios as $usuario) {
           // $userId = 10 + $i;
            $fechaRegistro = now()->subDays(rand(0, 180))->subMinutes(rand(0, 1440));

            Cliente::create([
                'user_id' => $usuario->id,
                'municipio' => $municipios[array_rand($municipios)],
                'colonia' => $colonias[array_rand($colonias)],
                'calle' => 'Calle Ficticia ' . rand(1, 500),
                'numero_exterior' => (string) rand(100, 999),
                'codigo_postal' => sprintf('%05d', rand(10000, 99999)),
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
    }
}