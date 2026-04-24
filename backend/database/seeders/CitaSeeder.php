<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\HorarioTrabajador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Cita::truncate();
        Schema::enableForeignKeyConstraints();

        $mascotasIds = Mascota::pluck('id')->toArray();
        $horariosIds = HorarioTrabajador::pluck('id')->toArray();
        $motivosMedicos = [
            'Consulta general de rutina para revisión del estado de salud.',
            'Aplicación de vacunas correspondientes según edad.',
            'Refuerzo de esquema de vacunación.',
            'Desparasitación preventiva interna.',  
            'Desparasitación externa (pulgas y garrapatas).',
            'Revisión por pérdida de apetito reciente.',
            'Consulta por vómitos frecuentes.',
            'Evaluación por diarrea persistente.',
            'Revisión por letargo o falta de energía.',
            'Consulta por cambios de comportamiento.',
            'Revisión por posible infección en la piel.',
            'Tratamiento de heridas leves.',
            'Evaluación de caída excesiva de pelo.',
            'Consulta por picazón constante.',
            'Revisión de oídos por posible infección.',
            'Consulta por mal olor en oídos.',
            'Evaluación de ojos irritados o con secreción.',
            'Revisión dental y limpieza.',
            'Consulta por mal aliento persistente.',
            'Chequeo previo a cirugía.',
            'Seguimiento postoperatorio.',
            'Consulta por cojera o dificultad para caminar.',
            'Evaluación de dolor en extremidades.',
            'Consulta por golpes o traumatismos recientes.',
            'Revisión por dificultad para respirar.',
            'Consulta por tos frecuente.',
            'Evaluación de fiebre.',
            'Control de peso y nutrición.',
            'Consulta por obesidad o sobrepeso.',
            'Asesoría nutricional.',
            'Chequeo geriátrico.',
            'Consulta para esterilización.',
            'Seguimiento después de esterilización.',
            'Consulta por comportamiento agresivo.',
            'Evaluación de ansiedad o estrés.',
            'Revisión por convulsiones.',
            'Consulta por intoxicación o ingestión de sustancias.',
            'Evaluación de alergias.',
            'Consulta por secreciones anormales.',
            'Revisión de crecimiento o desarrollo.',
            'Consulta por problemas urinarios.',
            'Evaluación de sangre en orina.',
            'Revisión por estreñimiento.',
            'Consulta por dificultad para defecar.',
            'Evaluación de problemas reproductivos.',
            'Consulta por celo o cambios hormonales.',
            'Chequeo previo a viaje.',
            'Emisión de certificado de salud.',
            'Consulta preventiva general.',
            'Revisión completa anual.'
        ];

        $motivosEsteticos = [
            'Baño estético general con productos especializados.',
            'Corte de pelo según raza y estilo.',
            'Recorte de uñas.',
            'Limpieza de oídos estética.',
            'Limpieza básica de ojos.',
            'Desenredado de pelaje.',
            'Cepillado profundo.',
            'Baño antipulgas estético.',
            'Baño con hidratación de piel y pelaje.',
            'Corte higiénico en zonas sensibles.',
            'Perfilado de patas.',
            'Perfilado de rostro.',
            'Corte completo de pelaje.',
            'Deslanado para control de muda.',
            'Baño medicado estético.',
            'Aplicación de acondicionador especial.',
            'Eliminación de nudos severos.',
            'Peinado y acabado final.',
            'Tratamiento de brillo para el pelaje.',
            'Limpieza estética de almohadillas.',
            'Recorte de pelo en almohadillas.',
            'Baño en seco.',
            'Aplicación de perfume para mascotas.',
            'Corte tipo cachorro.',
            'Corte tipo león.',
            'Corte personalizado.',
            'Tratamiento anti-olor.',
            'Baño relajante.',
            'Masaje durante el baño.',
            'Hidratación profunda de piel.',
            'Recorte de bigotes (opcional).',
            'Limpieza facial estética.',
            'Desparasitación estética superficial.',
            'Baño con productos hipoalergénicos.',
            'Corte de pelo para temporada de calor.',
            'Corte de pelo para temporada de frío.',
            'Preparación estética para evento.',
            'Arreglo completo premium.',
            'Baño express.',
            'Cepillado anti-caída.',
            'Tratamiento revitalizante de pelaje.',
            'Control estético de muda.',
            'Limpieza general estética.',
            'Recorte parcial de pelaje.',
            'Baño con espuma especializada.',
            'Acondicionamiento de pelaje largo.',
            'Peinado decorativo.',
            'Baño con aromaterapia.',
            'Servicio completo de grooming.',
            'Arreglo estético integral.'
        ];

        $motivosMixtos = [
            'Consulta general con baño estético incluido.',
            'Revisión médica básica acompañada de corte de uñas.',
            'Chequeo preventivo con limpieza de oídos.',
            'Consulta por piel sensible con baño medicado.',
            'Revisión general con cepillado profundo.',
            'Consulta dermatológica con tratamiento estético.',
            'Evaluación de pelaje con deslanado.',
            'Revisión por alergias con baño hipoalergénico.',
            'Chequeo general con limpieza facial.',
            'Consulta médica con corte higiénico.',
            'Revisión por caída de pelo con tratamiento estético.',
            'Consulta por picazón con baño especializado.',
            'Evaluación general con acondicionamiento de pelaje.',
            'Chequeo preventivo con perfilado básico.',
            'Consulta por infecciones leves con limpieza estética.',
            'Revisión médica con baño antipulgas.',
            'Consulta general con tratamiento anti-olor.',
            'Evaluación de piel con hidratación profunda.',
            'Revisión general con peinado y acabado.',
            'Consulta por problemas de pelaje con desenredado.',
            'Chequeo médico con baño relajante.',
            'Consulta por estrés con servicio estético calmante.',
            'Revisión general con corte parcial de pelo.',
            'Consulta dermatológica con eliminación de nudos.',
            'Evaluación médica con limpieza de almohadillas.',
            'Chequeo preventivo con recorte en zonas sensibles.',
            'Consulta por mal olor con baño especializado.',
            'Revisión general con cepillado anti-caída.',
            'Consulta médica con tratamiento revitalizante.',
            'Evaluación de piel con baño medicado estético.',
            'Chequeo general con arreglo básico.',
            'Consulta por irritación con limpieza y cuidado estético.',
            'Revisión médica con baño express.',
            'Consulta por alergias con productos especiales.',
            'Evaluación general con tratamiento de brillo.',
            'Chequeo médico con grooming básico.',
            'Consulta por muda excesiva con deslanado.',
            'Revisión general con limpieza integral.',
            'Consulta por piel reseca con hidratación.',
            'Evaluación médica con corte completo.',
            'Chequeo preventivo con baño en seco.',
            'Consulta por problemas cutáneos con tratamiento estético.',
            'Revisión general con perfilado de rostro.',
            'Consulta médica con servicio completo de grooming.',
            'Evaluación de salud con arreglo estético integral.',
            'Chequeo general con limpieza profunda.',
            'Consulta por sensibilidad cutánea con baño especial.',
            'Revisión médica con acondicionamiento de pelaje.',
            'Consulta general con servicio estético premium.',
            'Evaluación completa con baño y corte.'
        ];

        if (empty($mascotasIds) || empty($horariosIds)) {
            $this->command->error('Error: Faltan Mascotas o Horarios de Trabajador.');
            return;
        }

        $estados = ['agendada', 'en_proceso', 'realizada', 'cancelada'];
        $tipos = ['medico', 'estetico', 'mixto'];
    /*
        Cita::create([
            'fecha' => now()->toDateString(),
            'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
            'estado' => 'en_proceso',
            'tipo' => 'medico',
            'descripcion' => 'Urgencia: Revisión de herida',
            'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)]
        ]);
    */    /*
        for ($i = 0; $i < 100; $i++) {
            $fechaCita = now()->addDays(rand(-30, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[array_rand($estados)],
                'tipo' => $tipos[array_rand($tipos)],
                'descripcion' => 'Consulta de rutina ' . ($i + 1),
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }*/

    //citas canceladas
       //medico

        for($i = 0; $i<22;$i++){
            $fechaCita = now()->addDays(rand(-30, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[3],
                'tipo' => $tipos[0],
                'descripcion' => $motivosMedicos[array_rand($motivosMedicos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //estetico

        for($i = 0; $i<19;$i++){
            $fechaCita = now()->addDays(rand(-30, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[3],
                'tipo' => $tipos[1],
                'descripcion' => $motivosEsteticos[array_rand($motivosEsteticos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //mixto

        for($i = 0; $i<32;$i++){
            $fechaCita = now()->addDays(rand(-30, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[3],
                'tipo' => $tipos[2],
                'descripcion' => $motivosMixtos[array_rand($motivosMixtos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }


    //en_proceso
        //medico
        for($i = 0; $i<23;$i++){
            $fechaCita = now()->addDays(rand(-30, -1));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[1],
                'tipo' => $tipos[0],
                'descripcion' => $motivosMedicos[array_rand($motivosMedicos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //estetico

        for($i = 0; $i<19;$i++){
            $fechaCita = now()->addDays(rand(-30, -1));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[1],
                'tipo' => $tipos[1],
                'descripcion' => $motivosEsteticos[array_rand($motivosEsteticos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //mixto

        for($i = 0; $i<32;$i++){
            $fechaCita = now()->addDays(rand(-30, -1));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[1],
                'tipo' => $tipos[2],
                'descripcion' => $motivosMixtos[array_rand($motivosMixtos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
    

    //agendadas
        //medico
        for($i = 0; $i<23;$i++){
            $fechaCita = now()->addDays(rand(0, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[0],
                'tipo' => $tipos[0],
                'descripcion' => $motivosMedicos[array_rand($motivosMedicos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //estetico
        
        for($i = 0; $i<19;$i++){
            $fechaCita = now()->addDays(rand(0, 60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[0],
                'tipo' => $tipos[1],
                'descripcion' => $motivosEsteticos[array_rand($motivosEsteticos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }
        //mixto

        for($i = 0; $i<32;$i++){
            $fechaCita = now()->addDays(rand(0,60));
            $fechaRegistro = $fechaCita->copy()->subDays(rand(1, 5));

            Cita::create([
                'fecha' => $fechaCita->toDateString(),
                'mascota_id' => $mascotasIds[array_rand($mascotasIds)],
                'estado' => $estados[0],
                'tipo' => $tipos[2],
                'descripcion' => $motivosMixtos[array_rand($motivosMixtos)],
                'horario_trabajador_id' => $horariosIds[array_rand($horariosIds)],
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro
            ]);
        }


    }
}