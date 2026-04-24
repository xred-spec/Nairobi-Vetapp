<?php

use App\Models\Mascota;
use App\Models\User;
use App\Models\Animales;
use App\Models\Raza;
use App\Models\Cliente;
use App\Models\Trabajador;
use App\Models\Rol;
use App\Models\Productos;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

#3 Test Mario
# Verificar que se cree un producto correctamente
test('Verificar que se cree un producto correctamente', function() {
    $rol = Rol::factory()->create(['nombre' => 'administrador']); 
    $user = User::factory()->create(['rol_id' => $rol->id]);    
    $cliente = Cliente::factory()->create(['user_id' => $user->id]);
    $datosProducto = [
        'nombre' => 'Shampoo para dinosaurios',
        'descripcion' => 'Shampoo especialmente formulado para el cuidado de la piel de los dinosaurios.',
        'precio_compra' => 15000,
        'precio_venta' => 33000,
        'stock' => 10,
        'cantidad' => 10,
        'visibilidad' => 'visible',
        'marca' => 'RexCare',
        'medida' => 'litros',
        'cliente_id' => $cliente->id
    ];
    $response = $this->actingAs($user)->postJson('/api/v1/producto/store', $datosProducto);    
    $response->assertStatus(201);
    $response->assertJsonFragment([
        'nombre' => 'Shampoo para dinosaurios',
        'precio_compra' => 15000,
        'precio_venta' => 33000,
        'stock' => 10,
        'cantidad' => 10,
        'visibilidad' => 'visible',
        'marca' => 'RexCare',
        'medida' => 'litros'
    ]); 
});

#4
# Verificar que se pueda modificar un producto existente
test('Verificar que se pueda modificar un producto existente', function() {
    $rol = Rol::factory()->create(['nombre' => 'administrador']); 
    $user = User::factory()->create(['rol_id' => $rol->id]);    
    
    $producto = Productos::factory()->create([
        'nombre' => 'Shampoo viejo'
    ]);
        $datosActualizados = [
        'nombre' => 'Shampoo nuevo',
        'precio_compra' => 15000,
        'precio_venta' => 45000,
        'stock' => 15,
        'cantidad' => 15,
        'visibilidad' => 'visible',
        'marca' => 'RexCare',
        'medida' => 'litros'
    ];
    $response = $this->actingAs($user)->patchJson("/api/v1/producto/update/{$producto->id}", $datosActualizados);
    $response->assertStatus(200);
    $response->assertJsonFragment([
        'nombre' => 'Shampoo nuevo',
        'precio_venta' => 45000
    ]);
});

#5
# Verificar que se pueda borrar un reg correctamente
test('Verificar que se pueda borrar un reg correctamente', function() {
    $rol = Rol::factory()->create(['nombre' => 'administrador']); 
    $user = User::factory()->create(['rol_id' => $rol->id]);    
    $producto = Productos::factory()->create(); 
    $response = $this->actingAs($user)->deleteJson("/api/v1/producto/delete/{$producto->id}");
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Producto eliminado correctamente'
    ]);
});

#6
# Buscar un producto no existente
test('Buscar un producto no existente', function() {
    $rol = Rol::factory()->create(['nombre' => 'administrador']); 
    $user = User::factory()->create(['rol_id' => $rol->id]);    
    $response = $this->actingAs($user)->postJson('/api/v1/producto/search', [
        'nombre' => 'Producto Fantasma Inexistente'
    ]);
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'No se encontraron productos con los criterios de búsqueda' 
    ]);
});

#7
# Cambiar visibilidad de un producto a no visible y verificar que no aparezca en la lista de productos visibles
test('Cambiar visibilidad de un producto a no visible y verificar que no aparezca en la lista de productos visibles', function() {
    $rol = Rol::factory()->create(['nombre' => 'administrador']); 
    $user = User::factory()->create(['rol_id' => $rol->id]);    
    $producto = Productos::factory()->create([
        'nombre' => 'Collar antipulgas',
        'visibilidad' => 'visible'
    ]);
    $this->actingAs($user)->patchJson("/api/v1/producto/update/{$producto->id}", [
        'nombre' => 'Collarantipulgas',
        'precio_compra' => 200,
        'precio_venta' => 500,
        'stock' => 10,
        'cantidad' => 10,
        'marca' => 'RexCare',
        'medida' => 'unidad',
        'visibilidad' => 'no visible' 
    ]);
    $response = $this->actingAs($user)->getJson('/api/v1/producto/index');    
    $response->assertStatus(200);
    $response->assertJsonMissing([
        'nombre' => 'Collarantipulgas'
    ]);
});