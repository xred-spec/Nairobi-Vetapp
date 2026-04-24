<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // inicio_hora
        //final_hora
        DB::table('horarios')->insert([
            'inicio_hora'=>'09:00',
            'final_hora'=>'09:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        DB::table('horarios')->insert([
            'inicio_hora'=>'09:30',
            'final_hora'=>'10:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        DB::table('horarios')->insert([
            'inicio_hora'=>'10:00',
            'final_hora'=>'10:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        DB::table('horarios')->insert([
            'inicio_hora'=>'10:30',
            'final_hora'=>'11:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        DB::table('horarios')->insert([
            'inicio_hora'=>'11:00',
            'final_hora'=>'11:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
        DB::table('horarios')->insert([
            'inicio_hora'=>'11:30',
            'final_hora'=>'12:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'12:00',
            'final_hora'=>'12:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'12:30',
            'final_hora'=>'13:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'13:00',
            'final_hora'=>'13:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'13:30',
            'final_hora'=>'14:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);


        DB::table('horarios')->insert([
            'inicio_hora'=>'14:00',
            'final_hora'=>'14:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'14:30',
            'final_hora'=>'15:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'15:00',
            'final_hora'=>'15:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'15:30',
            'final_hora'=>'16:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'16:00',
            'final_hora'=>'16:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'16:30',
            'final_hora'=>'17:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'17:00',
            'final_hora'=>'17:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'17:30',
            'final_hora'=>'18:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'18:00',
            'final_hora'=>'18:30',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        DB::table('horarios')->insert([
            'inicio_hora'=>'18:30',
            'final_hora'=>'19:00',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
    }
}
