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


test('crear un animal',function(){
    $rol = Rol::factory()->create(['nombre' => 'administrador']);
    $admin = User::factory()->create(['rol_id' => $rol->id]);
    $nombre = 'ajolote';
    $response = $this->actingAs($admin)->postJson('/api/v1/animal/store',[
        'nombre'=>$nombre
    ]);
    $response->assertStatus(201)->assertJsonFragment(['message'=>'Animal creado correctamente','error'=>null,'nombre'=>'ajolote']);
});


test('crear un animal repetido',function(){
    $rol = Rol::factory()->create(['nombre' => 'administrador']);
    $admin = User::factory()->create(['rol_id' => $rol->id]);
    $nombre = 'gato';
    $response = $this->actingAs($admin)->postJson('/api/v1/animal/store',[
        'nombre'=>$nombre
    ]);
    $response = $this->actingAs($admin)->postJson('/api/v1/animal/store',[
        'nombre'=>$nombre
    ]);
    $response->assertStatus(409)->assertJson(['data'=>null,'message'=>'Este animal ya esta registrado','error'=>null]);
});