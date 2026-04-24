<?php
use App\Models\Mascota;
use App\Models\User;
use App\Models\Animales;
use App\Models\Raza;
use App\Models\Cliente;
use App\Models\Trabajador;
use App\Models\Rol;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Astorga - cliente consultar mascotas visibles', function() {
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user = User::factory()->create(['rol_id' => $rol->id]);
    $cliente = Cliente::factory()->create(['user_id' => $user->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($user)->getJson('/api/v1/mascotas/');
    $response->assertStatus(200); //Ok
});

test('Astorga - cliente crear mascota', function() {
    //$this->withOutExceptionHandling();

    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user = User::factory()->create(['rol_id' => $rol->id]);
    $cliente = Cliente::factory()->create(['user_id' => $user->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ];

    $response = $this->actingAs($user)->postJson('/api/v1/mascotas/', $mascota);
    $response->assertStatus(201); //Created
});

/*
test('cliente crear mascota para otro cliente', function() {
    $this->withOutExceptionHandling();

    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $user2 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $cliente2 = Cliente::factory()->create(['user_id' => $user2->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente2->id
    ];

    $response = $this->actingAs($user1)->postJson('/api/v1/mascotas/', $mascota);
    $response->assertStatus(403); //Unauthorized
});


test('cliente consultar mascotas INvisibles', function() {
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user = User::factory()->create(['rol_id' => $rol->id]);
    $cliente = Cliente::factory()->create(['user_id' => $user->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($user)->getJson('/api/v1/mascotas/');
    $response->assertStatus(403); //Unauthorized
});
*/

test('Astorga - cliente consultar mascota de otro cliente', function() {
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $user2 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $cliente2 = Cliente::factory()->create(['user_id' => $user2->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente1->id
    ]);

    $response = $this->actingAs($user2)->getJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(403); //Unauthorized
});

test('Astorga - cliente editar mascota de otro cliente', function() {
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $user2 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $cliente2 = Cliente::factory()->create(['user_id' => $user2->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente1->id
    ]);

    $nuevaMascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente1->id
    ];

    $response = $this->actingAs($user2)->putJson('/api/v1/mascotas/' . $mascota->id, $nuevaMascota);
    $response->assertStatus(403); //Unauthorized
});

test('Astorga - cliente eliminar mascota de otro cliente', function() {
    $rol = Rol::factory()->create(['nombre' => 'cliente']);
    $user1 = User::factory()->create(['rol_id' => $rol->id]);
    $user2 = User::factory()->create(['rol_id' => $rol->id]);
    $cliente1 = Cliente::factory()->create(['user_id' => $user1->id]);
    $cliente2 = Cliente::factory()->create(['user_id' => $user2->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente1->id
    ]);

    $response = $this->actingAs($user2)->deleteJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(403); //Unauthorized
});

test('Astorga - administrador consultar mascotas visibles', function() {
    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userAdmin)->getJson('/api/v1/mascotas/');
    $response->assertStatus(200); //Ok
});

test('Astorga - administrador crear mascota para un cliente', function() {
    //$this->withOutExceptionHandling();

    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ];

    $response = $this->actingAs($userAdmin)->postJson('/api/v1/mascotas/', $mascota);
    $response->assertStatus(201); //Created
});

test('Astorga - administrador consultar mascotas INvisibles', function() {
    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userAdmin)->getJson('/api/v1/mascotas/');
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador consultar mascotas visibles', function() {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userTrabajador)->getJson('/api/v1/mascotas/');
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador consultar mascotas INvisibles', function() {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userTrabajador)->getJson('/api/v1/mascotas/');
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador crear mascota para un cliente', function() {
    //$this->withOutExceptionHandling();

    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ];

    $response = $this->actingAs($userTrabajador)->postJson('/api/v1/mascotas/', $mascota);
    $response->assertStatus(201); //Created
});

test('Astorga - administrador consultar mascota de un cliente', function() {
    //$this->withoutExceptionHandling();

    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userAdmin)->getJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(200); //Ok
});

test('Astorga - administrador editar mascota de otro cliente', function() {
    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $nuevaMascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ];

    $response = $this->actingAs($userAdmin)->putJson('/api/v1/mascotas/' . $mascota->id, $nuevaMascota);
    $response->assertStatus(200); //Ok
});

test('Astorga - administrador eliminar mascota de un cliente', function() {
    //$this->withoutExceptionHandling();

    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userAdmin)->deleteJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(204); //No content
});

test('Astorga - trabajador consultar mascota de un cliente', function() {
    //$this->withoutExceptionHandling();

    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userTrabajador)->getJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador editar mascota de otro cliente', function() {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $nuevaMascota = [
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ];

    $response = $this->actingAs($userTrabajador)->putJson('/api/v1/mascotas/' . $mascota->id, $nuevaMascota);
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador eliminar mascota de un cliente', function() {
    //$this->withoutExceptionHandling();

    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userTrabajador)->deleteJson('/api/v1/mascotas/' . $mascota->id);
    $response->assertStatus(204); //No content
});

test('Astorga - administrador cambiar estado', function() {
    $rolAdmin = Rol::factory()->create(['nombre' => 'administrador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userAdmin = User::factory()->create(['rol_id' => $rolAdmin->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userAdmin)->putJson('/api/v1/mascotas/cambiar-estado/' . $mascota->id);
    $response->assertStatus(200); //Ok
});

test('Astorga - trabajador cambiar estado', function() {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userTrabajador)->putJson('/api/v1/mascotas/cambiar-estado/' . $mascota->id);
    $response->assertStatus(200); //OK
});

test('Astorga - cliente cambiar estado', function() {
    $rolTrabajador = Rol::factory()->create(['nombre' => 'trabajador']);
    $rolCliente = Rol::factory()->create(['nombre' => 'cliente']);
    $userTrabajador = User::factory()->create(['rol_id' => $rolTrabajador->id]);
    $userCliente = User::factory()->create(['rol_id' => $rolCliente->id]);
    $cliente = Cliente::factory()->create(['user_id' => $userCliente->id]);
    $animal = Animales::factory()->create();
    $raza = Raza::factory()->create(['animal_id' => $animal->id]);

    $mascota = Mascota::factory()->create([
        'nombre' => 'nuevoNombre',
        'sexo' => 'macho',
        'descripcion' => 'nueva descripcion',
        'fecha_nacimiento' => '2020-01-01',
        'raza_id' => $raza->id,
        'cliente_id' => $cliente->id
    ]);

    $response = $this->actingAs($userCliente)->putJson('/api/v1/mascotas/cambiar-estado/' . $mascota->id);
    $response->assertStatus(403); //Authorized
});