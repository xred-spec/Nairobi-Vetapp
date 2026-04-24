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

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);



test('cliente mostrar obtener sus citas',function(){
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $response = $this->actingAs($user1)->postJson('/api/v1/cita/search/0',[]);
    $response->assertStatus(200)->assertJsonFragment(['data'=>[]]);
});


test('cliente buscar sus sitas en determinada fecha',function(){
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    //dd($user1->id);
    $response = $this->actingAs($user1)->postJson('/api/v1/cita/search/0',['fecha'=>'2026/04/04']);
    $response->assertStatus(200)->assertJsonFragment(['data'=>[]]);
});




