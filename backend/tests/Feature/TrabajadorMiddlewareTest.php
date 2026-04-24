<?php

use App\Models\User;
use App\Models\Rol;
use App\Http\Middleware\TrabajadorMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase; 

uses(RefreshDatabase::class);

#1 Test Mario
test('Test verificar que funcione el middleware de trabajador ', function () {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    Route::middleware([TrabajadorMiddleware::class])->get('/ruta-trabajador', function() use ($rolTrabajador) {
        return response()->json([
            'rol_id' => $rolTrabajador->nombre 
        ], 200);
    });
    $response = $this->actingAs($userTrabajador)->getJson('/ruta-trabajador');
    $response->assertStatus(200);
    $response->assertJsonFragment([
        'rol_id' => 'trabajador'
    ]);
});

#2 Test Mario 
test('Verificar que un usuario normal no pase el middleware de trabajador', function () {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolUsuario = Rol::factory()->create(['nombre' => 'cliente']);
    $userCliente = User::factory()->create(['rol_id' => $rolUsuario]);
    Route::middleware([TrabajadorMiddleware::class])->get('/ruta-trabajador', function() {
    return response()->json(['rol_id' => 2],200);
    });

    $response = $this->actingAs($userCliente)->getJson('/ruta-trabajador');
    $response->assertStatus(403);
    $response->assertJsonMissing([
        'rol_id' => 2
    ]);
});

