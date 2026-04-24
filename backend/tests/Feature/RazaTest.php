<?php

use App\Models\User;
use App\Models\Animales;
use App\Models\Raza;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// HELLPERS
const URL = '/api/v1/raza/';

function cliente() {
    $rol = Rol::firstOrCreate(['nombre' => 'cliente']);
    return User::factory()->create(['rol_id' => $rol->id]);
}

function administrador() {
    $rol = Rol::firstOrCreate(['nombre' => 'administrador']);
    return User::factory()->create(['rol_id' => $rol->id]);
}

function trabajador() {
    $rol = Rol::firstOrCreate(['nombre' => 'trabajador']);
    return User::factory()->create(['rol_id' => $rol->id]);
}

/**
 * pruebas de cliente
 */

test('Cesar - cliente no puede ver lista de razas', function() {
    $user = cliente();
    
    $response = $this->actingAs($user)->getJson(URL .'raza');
    
    $response->assertStatus(403)
             ->assertJsonFragment([
                'message' => 'Ocurrio un problema obteniendo las razas'
                ]);
})->group('cliente');

test('Cesar - cliente no puede crear una raza', function() {
    $user = cliente();
    $animal = Animales::factory()->create();

    $data = [
        'nombre' => 'Test',
        'animal_id' => $animal->id,
        'visibilidad' => 'visible'
    ];

    $response = $this->actingAs($user)->postJson(URL . 'raza', $data);
    $response->assertStatus(403)
             ->assertJsonFragment([
                'message' => 'Ocurrio un problema creando la raza'
                ]);
})->group('cliente');

/*
 *  pruebas de admin 
 */

test('Cesar - administrador puede ver lista de razas con paginacion', function() {
    $userAdmin = administrador();
    $animal = Animales::factory()->create();
    Raza::factory()->create(['animal_id' => $animal->id, 'nombre' => 'Raza Test']);

    $response = $this->actingAs($userAdmin)->getJson(URL . 'raza');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'data', 
                'paginacion' => [
                    'current_page', 
                    'last_page', 
                    'per_page',
                     'total'
                     ]
            ],
            'message'
        ])
        ->assertJsonFragment(['nombre' => 'Raza Test']);
})->group('administrador');

test('Cesar - administrador puede crear una raza', function() {
    $userAdmin = administrador();
    $animal = Animales::factory()->create();

    $data = [
        'nombre' => 'Pitbull',
        'animal_id' => $animal->id,
        'visibilidad' => 'visible'
    ];

    $response = $this->actingAs($userAdmin)->postJson(URL . 'raza', $data);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Raza creada correctamente',
            'data' => [
                'nombre' => 'Pitbull',
                'animal' => $animal->nombre 
            ]
        ]);
})->group('administrador');

test('Cesar - administrador puede actualizar una raza existente', function() {
    $userAdmin = administrador();
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id, 'nombre' => 'Nombre Viejo']);

    $updateData = [
        'nombre' => 'Nombre Nuevo',
        'animal_id' => $animal->id,
        'visibilidad' => 'visible'
    ];

    $response = $this->actingAs($userAdmin)->putJson(URL . 'raza/' . $raza->id, $updateData);

    $response->assertStatus(200)
        ->assertJsonPath('data.nombre', 'Nombre Nuevo')
        ->assertJsonPath('message', 'Raza actualizada correctamente');
})->group('administrador');

test('Cesar - administrador puede eliminar una raza', function() {
    $userAdmin = administrador();
    $raza = Raza::factory()->create();

    $response = $this->actingAs($userAdmin)->deleteJson(URL . 'raza/' . $raza->id);
    
    $response->assertStatus(204);
    $this->assertSoftDeleted('razas', ['id' => $raza->id]);
})->group('administrador');

test('Cesar - administrador puede cambiar el estado de visibilidad', function() {
    $userAdmin = administrador();
    $raza = Raza::factory()->create(['visibilidad' => 'visible']);

    $response = $this->actingAs($userAdmin)->patchJson(URL . 'cambiar-estado/' . $raza->id);

    $response->assertStatus(200)
        ->assertJsonPath('data.visibilidad', 'invisible')
        ->assertJsonPath('message', 'Visibilidad cambiada correctamente');
})->group('administrador');

/*
 * pruebas de trabajador
 */

test('Cesar - trabajador puede ver lista incluyendo razas invisibles', function() {
    $userTrabajador = trabajador();
    Raza::factory()->create(['visibilidad' => 'invisible', 'nombre' => 'Raza']);

    $response = $this->actingAs($userTrabajador)->getJson(URL . 'raza');

    $response->assertStatus(200)
             ->assertJsonFragment(['nombre' => 'Raza', 'visibilidad' => 'invisible']);
})->group('trabajador');

test('Cesar - trabajador no tiene permiso para eliminar', function() {
    $userTrabajador = trabajador();
    $raza = Raza::factory()->create();

    $response = $this->actingAs($userTrabajador)->deleteJson(URL . 'raza/' . $raza->id);
    
    $response->assertStatus(403)
             ->assertJsonFragment(['message' => 'Ocurrio un problema eliminando la raza']);
})->group('trabajador');

/**
 * usuarios sin autentincar
 */

test('Cesar - usuario anonimo recibe 401 en cualquier ruta de raza', function() {
    $this->getJson(URL .'raza')->assertStatus(401);
    $this->postJson(URL . 'raza', [])->assertStatus(401);
})->group('sinAutenticar');