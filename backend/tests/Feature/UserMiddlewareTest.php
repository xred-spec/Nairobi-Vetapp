<?php
use App\Models\User;
use App\Models\Cliente;
use App\Models\Trabajador;
use App\Models\Rol;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase; 

uses(RefreshDatabase::class);

test('Astorga - admin middleware con usuario admin', function () {
    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);

    Route::middleware([AdminMiddleware::class])->get('/ruta-admin', function() {
        return response()->json('Acceso autorizado a admin', 200);
    });

    $response = $this->actingAs($userAdmin)->getJson('/ruta-admin');
    $response->assertStatus(200);
});

test('Astorga - admin middleware con usuario trabajador', function () {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);

    Route::middleware([AdminMiddleware::class])->get('/ruta-admin', function() {
        return response()->json('Acceso autorizado a admin', 200);
    });

    $response = $this->actingAs($userTrabajador)->getJson('/ruta-admin');
    $response->assertStatus(403);
});

test('Astorga - admin middleware con usuario cliente', function () {
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);

    Route::middleware([AdminMiddleware::class])->get('/ruta-admin', function() {
        return response()->json('Acceso autorizado a admin', 200);
    });

    $response = $this->actingAs($userCliente)->getJson('/ruta-admin');
    $response->assertStatus(403);
});
