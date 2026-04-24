<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Raza;
class RazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERROS (animal_id: 1)
        Raza::firstOrCreate(
            ['nombre' => 'Labrador Retriever', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );
        
        Raza::firstOrCreate(
            ['nombre' => 'Bulldog', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Golden Retriever', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Pastor Alemán', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Poodle', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Beagle', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Chihuahua', 'animal_id' => 1],
            ['visibilidad' => 'visible']
        );
        
        // GATOS (animal_id: 2)
        Raza::firstOrCreate(
            ['nombre' => 'Persa', 'animal_id' => 2],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Siamés', 'animal_id' => 2],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Maine Coon', 'animal_id' => 2],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'British Shorthair', 'animal_id' => 2],
            ['visibilidad' => 'visible']
        );

        Raza::firstOrCreate(
            ['nombre' => 'Ragdoll', 'animal_id' => 2],
            ['visibilidad' => 'visible']
        );

        // CONEJOS (animal_id: 6)
        Raza::firstOrCreate([
            'nombre' => 'Holland Lop',
            'animal_id' => 6,
            'visibilidad' => 'visible'
        ]);

        Raza::firstOrCreate([
            'nombre' => 'Mini Rex',
            'animal_id' => 6,
            'visibilidad' => 'visible'
        ]);

        // HURONES (animal_id: 9)
        Raza::firstOrCreate([
            'nombre' => 'Hurón Estándar',
            'animal_id' => 9,
            'visibilidad' => 'visible'
        ]);

        // AVES - Perico (animal_id: 10)
        Raza::firstOrCreate([
            'nombre' => 'Perico Australiano',
            'animal_id' => 10,
            'visibilidad' => 'visible'
        ]);

        // AVES - Loro (animal_id: 11)
        Raza::firstOrCreate([
            'nombre' => 'Loro Gris',
            'animal_id' => 11,
            'visibilidad' => 'visible'
        ]);

        // AVES - Canario (animal_id: 12)
        Raza::firstOrCreate([
            'nombre' => 'Canario Amarillo',
            'animal_id' => 12,
            'visibilidad' => 'visible'
        ]);

        // REPTILES - Iguana (animal_id: 3)
        Raza::firstOrCreate([
            'nombre' => 'Iguana Verde',
            'animal_id' => 3,
            'visibilidad' => 'visible'
        ]);

        // REPTILES - Tortuga (animal_id: 4)
        Raza::firstOrCreate([
            'nombre' => 'Tortuga Terrestre',
            'animal_id' => 4,
            'visibilidad' => 'visible'
        ]);

        // REPTILES - Camaleón (animal_id: 5)
        Raza::firstOrCreate([
            'nombre' => 'Camaleón Velado',
            'animal_id' => 5,
            'visibilidad' => 'visible'
        ]);

        // REPTILES - Serpiente (animal_id: 13)
        Raza::firstOrCreate([
            'nombre' => 'Serpiente Maíz',
            'animal_id' => 13,
            'visibilidad' => 'visible'
        ]);

        // PEQUEÑOS MAMÍFEROS - Hámster (animal_id: 7)
        Raza::firstOrCreate([
            'nombre' => 'Hámster Sirio',
            'animal_id' => 7,
            'visibilidad' => 'visible'
        ]);

        // PEQUEÑOS MAMÍFEROS - Cuy (animal_id: 8)
        Raza::firstOrCreate([
            'nombre' => 'Cuy Peruano',
            'animal_id' => 8,
            'visibilidad' => 'visible'
        ]);

        // PEQUEÑOS MAMÍFEROS - Erizo (animal_id: 14)
        Raza::firstOrCreate([
            'nombre' => 'Erizo Africano',
            'animal_id' => 14,
            'visibilidad' => 'visible'
        ]);

        // GRANJERO - Oveja (animal_id: 15)
        Raza::firstOrCreate([
            'nombre' => 'Oveja Suffolk',
            'animal_id' => 15,
            'visibilidad' => 'visible'
        ]);


        // GRANJERO - Vaca (animal_id: 16)
        Raza::firstOrCreate([
            'nombre' => 'Vaca Holstein',
            'animal_id' => 16,
            'visibilidad' => 'visible'
        ]);

        // MÁS PERROS (animal_id: 1)
        $dogBreeds = [
            'Dachshund', 'Shih Tzu', 'Pug', 'French Bulldog', 'Boxer', 'Rottweiler', 'Doberman',
            'Husky Siberiano', 'Malamute', 'San Bernardo', 'Gran Danés', 'Mastín', 'Shar Pei',
            'Jack Russell Terrier', 'Border Collie', 'Australian Shepherd', 'Corgi', 'Dálmata',
            'Weimaraner', 'Vizsla', 'Basset Hound', 'Bloodhound', 'Rhodesian Ridgeback', 'Akita',
            'Shiba Inu', 'Samoyedo', 'Chow Chow', 'Basenji', 'Lhasa Apso'
        ];
        foreach ($dogBreeds as $breed) {
            Raza::firstOrCreate([
                'nombre' => $breed,
                'animal_id' => 1
            ], ['visibilidad' => 'visible']);
        }

        // MÁS GATOS (animal_id: 2)
        $catBreeds = [
            'Esfinge', 'Bengala', 'Abisinio', 'Scottish Fold', 'Gato de Gato', 'Orientales', 'Korat',
            'Egipcio Mau', 'Siberiano', 'Nórico', 'Bobtail Americano', 'Selkirk Rex', 'LaPerm',
            'Tonkinese', 'Burmés', 'Havana Brown', 'American Shorthair', 'Exótico', 'Colorado Rex'
        ];
        foreach ($catBreeds as $breed) {
            Raza::firstOrCreate([
                'nombre' => $breed,
                'animal_id' => 2
            ], ['visibilidad' => 'visible']);
        }

        // MÁS RAZAS PARA CONEJOS (animal_id: 6)
        $rabbitBreeds = [
            'Lionhead', 'Netherland Dwarf', 'Flemish Giant', 'Angora', 'Rex', 'Satin', 'Dwarf Hotot'
        ];
        foreach ($rabbitBreeds as $breed) {
            Raza::firstOrCreate([
                'nombre' => $breed,
                'animal_id' => 6
            ], ['visibilidad' => 'visible']);
        }

        // MÁS HÁMSTER (animal_id: 7)
        $hamsterBreeds = [
            'Hámster Enano', 'Hámster Chino', 'Hámster Roborowski'
        ];
        foreach ($hamsterBreeds as $breed) {
            Raza::firstOrCreate([
                'nombre' => $breed,
                'animal_id' => 7
            ], ['visibilidad' => 'visible']);
        }

        // MÁS CUY (animal_id: 8)
        $cuyBreeds = [
            'Cuy Americano', 'Cuy Abisinio', 'Cuy Peruvian'
        ];
        foreach ($cuyBreeds as $breed) {
            Raza::firstOrCreate([
                'nombre' => $breed,
                'animal_id' => 8
            ], ['visibilidad' => 'visible']);
        }

        // RAZAS PARA PECES (animal_ids aproximados post-seed, ajustar si necesario)
        // Pez Betta (~20), Goldfish (~21), etc.
        Raza::firstOrCreate(['nombre' => 'Betta Halfmoon', 'animal_id' => 20], ['visibilidad' => 'visible']);
        Raza::firstOrCreate(['nombre' => 'Betta Crowntail', 'animal_id' => 20], ['visibilidad' => 'visible']);
        Raza::firstOrCreate(['nombre' => 'Comet Goldfish', 'animal_id' => 21], ['visibilidad' => 'visible']);
        Raza::firstOrCreate(['nombre' => 'Ryukin Goldfish', 'animal_id' => 21], ['visibilidad' => 'visible']);
        Raza::firstOrCreate(['nombre' => 'Koi Kohaku', 'animal_id' => 22], ['visibilidad' => 'visible']);
        // Add more fish, birds, etc. as needed...

        // Placeholder for more exóticos post-seed
    }
}

