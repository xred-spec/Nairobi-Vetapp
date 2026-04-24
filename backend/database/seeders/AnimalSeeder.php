<?php

namespace Database\Seeders;

use App\Models\Animales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Animales::firstOrCreate(
            ['nombre' => 'Perro'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Gato'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Iguana'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Tortuga'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Camaleón'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Conejo'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Hámster'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Cuy'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Hurón'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Perico'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Loro'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Canario'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Serpiente'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Erizo'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Oveja'],
            ['visibilidad' => 'visible']
        );
        Animales::firstOrCreate(
            ['nombre' => 'Vaca'],
            ['visibilidad' => 'visible']
        );

        Animales::firstOrCreate(
            ['nombre' => 'Cerdo'],
            ['visibilidad' => 'invisible']
        );

        // Peces
        Animales::firstOrCreate(['nombre' => 'Pez Betta'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Goldfish'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Koi'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Guppy'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Tetras'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Angelfish'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Discus'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Corydoras'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Neon Tetra'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Molly'], ['visibilidad' => 'visible']);

        // Más aves
        Animales::firstOrCreate(['nombre' => 'Aguacate'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Cotorro'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Lovebird'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Cacatúa'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Guacamaya'], ['visibilidad' => 'visible']);

        // Roedores adicionales
        Animales::firstOrCreate(['nombre' => 'Jerbo'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Ratón'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Chinchilla'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Degú'], ['visibilidad' => 'visible']);

        // Granja
        Animales::firstOrCreate(['nombre' => 'Caballo'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Cabra'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Pavo'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Pato'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Ganso'], ['visibilidad' => 'visible']);

        // Exóticos/Reptiles
        Animales::firstOrCreate(['nombre' => 'Dragón Barbudo'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Lagarto'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Pitón'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Boa'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Tarántula'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Escorpión'], ['visibilidad' => 'visible']);

        // Anfibios
        Animales::firstOrCreate(['nombre' => 'Axolotl'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Rana'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Salamandra'], ['visibilidad' => 'visible']);

        // Más mamíferos pequeños
        Animales::firstOrCreate(['nombre' => 'Cobaya'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Ardilla'], ['visibilidad' => 'visible']);

        // Otros exóticos
        Animales::firstOrCreate(['nombre' => 'Mono'], ['visibilidad' => 'invisible']);
        Animales::firstOrCreate(['nombre' => 'Lémur'], ['visibilidad' => 'invisible']);
        Animales::firstOrCreate(['nombre' => 'Tigre'], ['visibilidad' => 'invisible']);
        Animales::firstOrCreate(['nombre' => 'León'], ['visibilidad' => 'invisible']);

        // Peces ornamentales adicionales
        Animales::firstOrCreate(['nombre' => 'Kuhli Loach'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Platy'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Swordtail'], ['visibilidad' => 'visible']);

        // Aves exóticas
        Animales::firstOrCreate(['nombre' => 'Ninfa'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Yaco'], ['visibilidad' => 'visible']);

        // Reptiles marinos
        Animales::firstOrCreate(['nombre' => 'Tortuga Marina'], ['visibilidad' => 'visible']);

        // Mamíferos grandes
        Animales::firstOrCreate(['nombre' => 'Burro'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Llama'], ['visibilidad' => 'visible']);

        // Invertebrados
        Animales::firstOrCreate(['nombre' => 'Caracol'], ['visibilidad' => 'visible']);
        Animales::firstOrCreate(['nombre' => 'Camarón'], ['visibilidad' => 'visible']);

    }
}
