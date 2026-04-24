<?php

use App\Models\Rol;
use App\Models\User;
use App\Models\Servicios;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin consultar servicios', function() {
    $roladmin = Rol::factory()->create(['nombre' => 'administrador']);
    $useradmin = User::factory()->create(['rol_id' => $roladmin->id]);
    $servicio = Servicios::factory()->create();


    $response = $this->actingAs($useradmin)->getJson('/api/v1/servicio/index');
    $response->assertStatus(200); //Ok
});


test('administrador puede crear un servicio', function() {
    $roladmin = Rol::factory()->create(['nombre' => 'administrador']);
    $useradmin = User::factory()->create(['rol_id' => $roladmin->id]);
    $servicio = Servicios::factory()->create();

    $servicioDatos = [
        'nombre' => 'Servicio Nueva Admin',
        'descripcion' => 'Servicio perro',
        'visibilidad' => 'visible'
    ];

    $response = $this->actingAs($useradmin)->postJson('/api/v1/servicio/store', $servicioDatos);
    $response->assertStatus(201); //Created
});

test('Administrador puede actualizar',function(){
 $rolAdmin = Rol::factory()->create(['nombre'=>'administrador']);
 $userAdmin = User::factory()->create(['rol_id'=>$rolAdmin->id]);
 $servicio = Servicios::factory()->create();

 $servicioUpdate = [
    'nombre' => 'Servicio actualizado',
    'descripcion' =>'actualizado',
    'visibilidad'=> 'visible'
 ];

 $response = $this->actingAs($userAdmin)->patchJson("/api/v1/servicio/update/{$servicio->id}",$servicioUpdate);
 $response->assertStatus(200);
});

test('Eliminar un servicio',function(){
    $rolAdmin = Rol::factory()->create(['nombre'=>'administrador']);
    $userAdmin = User::factory()->create(['rol_id'=>$rolAdmin->id]);
    $servicio = Servicios::factory()->create();

 $response = $this->actingAs($userAdmin)->deleteJson("/api/v1/servicio/delete/{$servicio->id}");
 $response->assertStatus(200);
});

test('Actualizar estado',function(){
    $rolAdmin = Rol::factory()->create(['nombre'=>'administrador']);
    $userAdmin = User::factory()->create(['rol_id'=>$rolAdmin->id]);
    $servicio = Servicios::factory()->create(['visibilidad' => 'visible']);

    $response = $this->actingAs($userAdmin)->putJson("/api/v1/servicio/cambiar-estado/{$servicio->id}");
    $response->assertStatus(200);
    $response->assertJsonFragment(['visibilidad' => 'invisible']);
});