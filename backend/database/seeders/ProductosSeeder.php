<?php

namespace Database\Seeders;

use App\Models\Productos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Productos::truncate();
        Schema::enableForeignKeyConstraints();

        $categorias = [
            ['Alimento', 'Royal Canin', 'kilos', 200, 500],
            ['Alimento', 'Pro Plan', 'kilos', 180, 450],
            ['Farmacia', 'Zoetis', 'unidad', 50, 300],
            ['Farmacia', 'Bayer', 'unidad', 80, 400],
            ['Limpieza', 'Virbac', 'mililitros', 30, 150],
            ['Accesorios', 'Kong', 'unidad', 100, 350],
            ['Higiene', 'Pet Head', 'mililitros', 60, 200]
        ];

        $nombresBase = [
        // Vacunas
    ['Vacuna Rabia', 'unidad',1],
    ['Vacuna Quíntuple', 'unidad',1],
    ['Vacuna Séxtuple', 'unidad',1],
    ['Vacuna Séptuple', 'unidad',1],
    ['Vacuna Bordetella', 'unidad',1],
    ['Vacuna Leptospirosis', 'unidad',1],
    ['Vacuna Parvovirus', 'unidad',1],
    ['Vacuna Moquillo', 'unidad',1],
    ['Vacuna Leucemia Felina', 'unidad',1],
    ['Vacuna Triple Felina', 'unidad',1],

    // Desparasitantes
    ['Desparasitante Interno Perro', 'unidad',1],
    ['Desparasitante Interno Gato', 'unidad',1],
    ['Desparasitante Externo Spray', 'mililitros',200],
    ['Pipeta Antipulgas Perro', 'unidad',1],
    ['Pipeta Antipulgas Gato', 'unidad',1],
    ['Collar Antiparasitario Perro', 'unidad',1],
    ['Collar Antiparasitario Gato', 'unidad',1],
    ['Antiparasitario Oral Cachorro', 'unidad',1],
    ['Antiparasitario Oral Adulto', 'unidad',1],
    ['Desparasitante Pasta Gato', 'gramos',1],
    ['Desparasitante Comprimido Perro', 'unidad',1],

    // Antibióticos y medicamentos
    ['Antibiótico General Perro', 'unidad',1],
    ['Antibiótico General Gato', 'unidad',1],
    ['Amoxicilina Veterinaria', 'unidad',1],
    ['Enrofloxacina Veterinaria', 'unidad',1],
    ['Metronidazol Veterinario', 'unidad',1],
    ['Antiinflamatorio Canino', 'unidad',1],
    ['Antiinflamatorio Felino', 'unidad',1],
    ['Analgésico Veterinario', 'unidad',1],
    ['Corticoide Tópico', 'gramos',10],
    ['Corticoide Oral', 'unidad',10],
    ['Antifúngico Tópico', 'gramos',2],
    ['Antifúngico Oral', 'unidad',3],
    ['Antihistamínico Canino', 'unidad',4],
    ['Antihistamínico Felino', 'unidad',2],
    ['Gastroprotector Veterinario', 'unidad',1],
    ['Probiótico Canino', 'gramos',1],
    ['Probiótico Felino', 'gramos',1],
    ['Antidiarreico Veterinario', 'unidad',1],
    ['Antiemético Veterinario', 'unidad',1],
    ['Suero Oral Veterinario', 'mililitros',1],
    ['Sedante Oral Veterinario', 'unidad',1],
    ['Ansiolítico Canino', 'unidad',1],
    ['Ansiolítico Felino', 'unidad',1],
    ['Relajante Muscular Veterinario', 'unidad',1],

    // Oftalmología
    ['Colirio Antibiótico Perro', 'mililitros',1],
    ['Colirio Antibiótico Gato', 'mililitros',1],
    ['Lágrimas Artificiales Veterinarias', 'mililitros',1],
    ['Limpiador Ocular Canino', 'mililitros',1],
    ['Limpiador Ocular Felino', 'mililitros',1],
    ['Pomada Oftálmica Veterinaria', 'gramos',1],
    ['Gotas Antiinflamatorias Oculares', 'mililitros',2],

    // Dermatología
    ['Shampoo Antipulgas', 'mililitros',250],
    ['Shampoo Antimicótico', 'mililitros',340],
    ['Shampoo Antibacteriano', 'mililitros',420],
    ['Shampoo Pelo Blanco', 'mililitros',230],
    ['Shampoo Piel Sensible', 'mililitros',150],
    ['Shampoo Hidratante', 'mililitros',200],
    ['Shampoo Medicado Perro', 'mililitros',350],
    ['Shampoo Medicado Gato', 'mililitros',370],
    ['Acondicionador Veterinario', 'mililitros',230],
    ['Loción Dermatológica', 'mililitros',340],
    ['Crema Cicatrizante Veterinaria', 'gramos',1],
    ['Spray Cicatrizante', 'mililitros',1],
    ['Spray Antimicótico Tópico', 'mililitros',1],
    ['Spray Repelente Perro', 'mililitros',1],
    ['Spray Repelente Gato', 'mililitros',250],
    ['Talco Dermatológico', 'gramos',250],
    ['Bálsamo para Cojinetes', 'gramos',1],
    ['Protector Solar Veterinario', 'gramos',160],

    // Oídos
    ['Limpiador de Oídos Perro', 'mililitros',1],
    ['Limpiador de Oídos Gato', 'mililitros',1],
    ['Gotas Óticas Antibióticas', 'mililitros',1],
    ['Gotas Óticas Antimicóticas', 'mililitros',1],
    ['Gotas Óticas Antiinflamatorias', 'mililitros',1],
    ['Ceruminolítico Veterinario', 'mililitros',1],

    // Dental
    ['Pasta Dental Canina', 'gramos',1],
    ['Pasta Dental Felina', 'gramos',1],
    ['Spray Dental Veterinario', 'mililitros',1],
    ['Enjuague Bucal Veterinario', 'mililitros',1],
    ['Gel Dental Canino', 'gramos',1],
    ['Snacks Dentales Perro', 'gramos',1],
    ['Snacks Dentales Gato', 'gramos',1],

    // Nutrición médica y suplementos
    ['Alimento Renal Perro', 'kilos',50],
    ['Alimento Renal Gato', 'kilos',30],
    ['Alimento Gastrointestinal Perro', 'kilos',20],
    ['Alimento Gastrointestinal Gato', 'kilos',30],
    ['Alimento Hipoalergénico Perro', 'kilos',20],
    ['Alimento Hipoalergénico Gato', 'kilos',25],
    ['Alimento Diabético Perro', 'kilos',10],
    ['Alimento Diabético Gato', 'kilos',8],
    ['Alimento Urinario Gato', 'kilos',4],
    ['Alimento Recuperación Postquirúrgica', 'kilos',9],
    ['Suplemento Articular Canino', 'gramos',250],
    ['Suplemento Articular Felino', 'gramos',400],
    ['Suplemento Omega 3 Veterinario', 'unidad',1],
    ['Suplemento Vitamínico Cachorro', 'gramos',150],
    ['Suplemento Vitamínico Adulto', 'gramos',350],
    ['Suplemento Vitamínico Senior', 'gramos',120],
    ['Suplemento Dérmico Canino', 'gramos',200],
    ['Suplemento Dérmico Felino', 'gramos',300],
    ['Suplemento Hepático Canino', 'gramos',200],
    ['Suplemento Hepático Felino', 'gramos',420],
    ['Suplemento Renal Canino', 'gramos',500],

    // Estética consumible
    ['Colonia Veterinaria Perro', 'mililitros',1],
    ['Colonia Veterinaria Gato', 'mililitros',1],
    ['Perfume Hipoalergénico Canino', 'mililitros',1],
    ['Toallitas Húmedas Perro', 'unidad',80],
    ['Toallitas Húmedas Gato', 'unidad',80],
    ['Toallitas Limpiadoras Oculares', 'unidad',80],
    ['Removedor de Manchas Lágrimas', 'mililitros',1],
    ['Aclarante Pelo Blanco', 'mililitros',1],
    ['Gel Desenredante', 'gramos',1],

    // Cirugía y curas
    ['Vendaje Cohesivo Veterinario', 'unidad',1],
    ['Gasa Estéril Veterinaria', 'unidad',1],
    ['Jeringas Desechables Veterinarias', 'unidad',1],
    ['Guantes de Látex Veterinario', 'unidad',1],
    ['Solución Salina Veterinaria', 'mililitros',1],
    ['Alcohol Isopropílico Veterinario', 'mililitros',1],
    ['Antiséptico Yodado', 'mililitros',1],
    ['Cinta Adhesiva Médica Veterinaria', 'unidad',1],

    // Diagnóstico consumible
    ['Tiras Reactivas Orina Veterinaria', 'unidad',1],
    ['Kit Diagnóstico Parvovirus', 'unidad',1],
    ['Kit Diagnóstico Giardia', 'unidad',1],
    ['Kit Diagnóstico Leishmaniasis', 'unidad',1],
    ['Kit Diagnóstico Coronavirus Felino', 'unidad',1],
];

        for ($i = 0; $i < 148; $i++) {
            $cat = $categorias[array_rand($categorias)];
            $producto = $nombresBase[array_rand($nombresBase)]; 
            $precioCompra = rand($cat[3], $cat[4]);
            $precioVenta = $precioCompra + rand(50, 200);
            $fecha = now()->subDays(rand(10, 180))->subMinutes(rand(0, 1440));
            Productos::create([
                'nombre' => $producto[0],
                'stock' => rand(5, 100),
                'precio_compra' => $precioCompra,
                'precio_venta' => $precioVenta,
                'cantidad' => $producto[2],
                'marca' => $cat[1],
                'visibilidad' => 'visible',
                'medida' => $producto[1],
                'created_at' => $fecha,
                'updated_at' => $fecha
            ]);
        }
    }
}