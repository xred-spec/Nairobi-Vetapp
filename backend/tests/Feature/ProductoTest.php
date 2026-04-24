<?php

use App\Models\Mascota;
use App\Models\User;
use App\Models\Animales;
use App\Models\Raza;
use App\Models\Cliente;
use App\Models\Horario;
use App\Models\HorarioTrabajador;
use App\Models\Trabajador;
use App\Models\Rol;
use App\Models\Productos;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


test('cliente insertar un producto',function(){
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $response = $this->actingAs($user1)->postJson('/api/v1/producto/store',[
        "nombre"=> "desparasitante",
        "stock"=> 100,
        "precio_compra"=> 18.50,
        "precio_venta"=> 25.00,
        "cantidad"=> 1,
        "marca"=>'Nexgard',
        "visibilidad"=> "visible",
        "medida"=> "unidad"
    ]);
    $response->assertStatus(403)->assertJson([]);
});


test('administrador insertar un producto',function(){
    $rol = Rol::factory()->create(['nombre' => 'administrador']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $trabajador = Trabajador::factory()->create(['user_id' => $user1->id]);
    $response = $this->actingAs($user1)->postJson('/api/v1/producto/store',[
        "nombre"=> "desparasitante",
        "stock"=> 100,
        "precio_compra"=> 18.50,
        "precio_venta"=> 25.00,
        "cantidad"=> 1,
        "marca"=>'Nexgard',
        "visibilidad"=> "visible",
        "medida"=> "unidad"
    ]);
    $response->assertStatus(201)->assertJsonFragment(["nombre"=> "desparasitante"]);
});

test('borrado',function(){
    $rol = Rol::factory()->create(['nombre' => 'administrador']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $trabajador = Trabajador::factory()->create(['user_id' => $user1->id]);
    $producto = Productos::factory()->create();
    $response = $this->actingAs($user1)->delete('/api/v1/producto/delete/'.$producto->id,[
    ]);
     $response->assertStatus(200);
});