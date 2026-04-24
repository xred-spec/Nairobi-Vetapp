<?php

namespace Database\Seeders;

use App\Models\Servicios;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ServiciosSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Servicios::truncate();
        Schema::enableForeignKeyConstraints();
        $servicios = [
            ['nombre' => 'Consulta General', 'descripcion' => 'Revisión rutinaria del animal, chequeo vitales y diagnóstico básico.', 'visibilidad' => 'visible'],
            ['nombre' => 'Vacunación', 'descripcion' => 'Aplicación de vacunas según calendario.', 'visibilidad' => 'visible'],
            ['nombre' => 'Desparasitación', 'descripcion' => 'Eliminación de parásitos internos y externos.', 'visibilidad' => 'visible'],
            ['nombre' => 'Esterilización', 'descripcion' => 'Cirugía de castración o esterilización.', 'visibilidad' => 'visible'],
            ['nombre' => 'Análisis de Sangre', 'descripcion' => 'Estudios hematológicos y bioquímicos.', 'visibilidad' => 'visible'],
            ['nombre' => 'Radiografía', 'descripcion' => 'Estudio radiológico para diagnóstico.', 'visibilidad' => 'visible'],
            ['nombre' => 'Ecografía', 'descripcion' => 'Ultrasonido para órganos internos.', 'visibilidad' => 'visible'],
            ['nombre' => 'Cirugía Menor', 'descripcion' => 'Procedimientos quirúrgicos ambulatorios.', 'visibilidad' => 'visible'],
            ['nombre' => 'Baño y Grooming', 'descripcion' => 'Limpieza profesional y estética.', 'visibilidad' => 'visible'],
            ['nombre' => 'Corte de Uñas', 'descripcion' => 'Poda de uñas del animal.', 'visibilidad' => 'visible'],
            ['nombre' => 'Limpieza Dental', 'descripcion' => 'Profilaxis dental y raspado.', 'visibilidad' => 'visible'],
            ['nombre' => 'Control Pulgas/Garrapatas', 'descripcion' => 'Tratamiento antiparasitario externo.', 'visibilidad' => 'visible'],
            ['nombre' => 'Terapia Física', 'descripcion' => 'Rehabilitación y fisioterapia.', 'visibilidad' => 'visible'],
            ['nombre' => 'Consulta Nutricional', 'descripcion' => 'Asesoría dietética personalizada.', 'visibilidad' => 'visible'],
            ['nombre' => 'Atención de Urgencias', 'descripcion' => 'Servicio 24/7 para emergencias.', 'visibilidad' => 'visible'],
            ['nombre' => 'Odontología Avanzada', 'descripcion' => 'Tratamientos dentales especializados.', 'visibilidad' => 'visible'],
            ['nombre' => 'Cardiología Veterinaria', 'descripcion' => 'Diagnóstico y tratamiento cardíaco.', 'visibilidad' => 'visible'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Tratamiento de enfermedades de piel.', 'visibilidad' => 'visible'],
            ['nombre' => 'Oncología', 'descripcion' => 'Tratamiento del cáncer en mascotas.', 'visibilidad' => 'visible'],
            ['nombre' => 'Neurología', 'descripcion' => 'Diagnóstico neurológico y tratamiento.', 'visibilidad' => 'visible'],
            ['nombre' => 'Oftalmología', 'descripcion' => 'Atención de problemas oculares.', 'visibilidad' => 'visible'],
            ['nombre' => 'Ortopedia', 'descripcion' => 'Cirugías y tratamientos óseos.', 'visibilidad' => 'visible'],
            ['nombre' => 'Acupuntura Veterinaria', 'descripcion' => 'Terapia alternativa para dolor.', 'visibilidad' => 'visible'],
            ['nombre' => 'Homeopatía', 'descripcion' => 'Tratamientos naturales.', 'visibilidad' => 'visible'],
            ['nombre' => 'Hidroterapia', 'descripcion' => 'Rehabilitación en agua.', 'visibilidad' => 'visible'],
        ];

foreach ($servicios as $data) {
            Servicios::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}

