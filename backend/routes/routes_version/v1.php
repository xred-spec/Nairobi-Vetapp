<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RazaController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ClienteController;
use Illuminate\Http\Request;    
use App\Mail\ReportsMail;
use Illuminate\Support\Facades\Mail;

Route::get('/correo',function(){
//new ReportsMail;
Mail::to('s4dless123@gmail.com')->send(new ReportsMail);
return response()->json(['correo'=>'correo_enviado']);
});
// autenticación y manejo de cuentas
Route::prefix('auth')->group(function(){
Route::get('/user',[AuthController::class,'user'])->middleware('auth:sanctum');
Route::post('/createUserdigital',[AuthController::class,'CreateUserDigital']);
Route::post('/createUserirl',[AuthController::class,'CreateUserIRL']);
Route::post('/createTrabajador',[AuthController::class,'CreateTrabajador']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::patch('/verificarcuenta',[AuthController::class,'VerificarCuenta']);
Route::post('/generatetoken',[AuthController::class,'GenerateToken']);
Route::post('/reclamarcuenta',[AuthController::class,'ReclamarCuenta']);
Route::post('/verificartoken',[AuthController::class,'ValidarToken']);
Route::post('/recuperarpassword',[AuthController::class,'RecuperarPassword']);
Route::patch('/setpassword',[AuthController::class,'SetContraseña']);
});

// reportes
Route::middleware(['auth:sanctum', AdminMiddleware::class])->prefix('reporte')->group(function(){
    Route::post('/get-reporte', [ReporteController::class, 'getReporte']);
    Route::post('/getReporte2',[ReporteController::class,'getReporte2']);
    Route::post('/distribucion-tipo',[ReporteController::class,'distribucionTipo']);
});

Route::middleware('auth:sanctum')->prefix('producto')->group(function(){
    Route::get('/index2',[ProductoController::class,'index2']);
    Route::get('/index',[ProductoController::class,'index']);
    Route::post('/store',[ProductoController::class,'store']);
    Route::patch('/update/{id}',[ProductoController::class,'update']);
    Route::post('/search',[ProductoController::class,'search']);
    Route::delete('/delete/{id}',[ProductoController::class,'delete']);
});

//  tes
Route::middleware('auth:sanctum')->prefix('user')->group(function(){
    Route::get('/index', [UserController::class, 'indexClientes']);
    Route::get('/full', [UserController::class, 'fullClientes']);
    Route::get('/get', [UserController::class, 'showProfile']);
    Route::patch('/password', [UserController::class, 'updatePassword']);
    Route::patch('/updatedatos', [UserController::class, 'update']);
    Route::patch('/updatecasa', [ClienteController::class, 'update']);
});



// manejo de animales
Route::middleware('auth:sanctum')->prefix('animal')->group(function () {
    Route::get('/index', [AnimalController::class, 'index']);
    Route::Post('store', [AnimalController::class, 'store']);
    Route::Patch('/update/{id}', [AnimalController::class, 'update']);
    Route::get('full', [AnimalController::class, 'fullList']);
    Route::get('fullAndroid', [AnimalController::class, 'fullAndroid']);
    Route::delete('/delete/{id}', [AnimalController::class, 'delete']);
    Route::put('/cambiar-estado/{id}', [AnimalController::class, 'switchState']);
});

// manejo de razas
Route::middleware('auth:sanctum')->prefix('raza')->group(function(){
Route::apiResource('raza',RazaController::class); 
Route::patch('cambiar-estado/{id}',[RazaController::class,'cambiarEstado']); 
Route::get('full', [RazaController::class, 'fullList']);
Route::get('fullAndroid', [RazaController::class, 'fullAndroid']);
});


Route::middleware('auth:sanctum')->prefix('cita')->group(function(){
Route::get('/index',[CitaController::class,'index']);
//Route::get('/index/{id}',[CitaController::class,'index']);
Route::post('/store',[CitaController::class,'store']);
Route::patch('/update/{id}',[CitaController::class,'update']);
Route::patch('/cancel/{id}',[CitaController::class,'cancel']);
Route::patch('/changeStatus/{id}',[CitaController::class,'changeStatus']);
Route::post('/search/{history?}',[CitaController::class,'search']);
//endpoints para generar citas --> buscadores
Route::get('/prefetching-cita',[CitaController::class,'CitaFetchingCreate']);
Route::get('/prefetchingeditcita/{id}',[CitaController::class,'prefetchingeditcita']);
Route::post('/clientes',[CitaController::class,'getClientes']);
Route::get('/mascotas/{id}',[CitaController::class,'getMascotasCitas']);
Route::get('/disponibilidad/{trabajador}/{fecha}',[CitaController::class,'getDisponibilidadHora']);
});

// manejo de mascotas
Route::prefix('mascotas')
->middleware('auth:sanctum')
->group(function() {
    Route::get('/', [MascotaController::class, 'index']);
    Route::get('/visibles', [MascotaController::class, 'indexVisibles']);
    Route::get('full-index', [MascotaController::class, 'fullIndex']);
    Route::post('/search', [MascotaController::class, 'search']);
    Route::post('/', [MascotaController::class, 'store']);
    Route::get('/{id}', [MascotaController::class, 'show']);
    Route::put('/{id}', [MascotaController::class, 'update']);
    Route::put('/cambiar-estado/{id}', [MascotaController::class, 'cambiarEstado']);
    Route::delete('/{id}', [MascotaController::class, 'destroy']);
});

// manejo de consultas
Route::prefix('consulta')->middleware('auth:sanctum')->group(function () {
    Route::post('/store', [ConsultaController::class, 'store']);
    Route::patch('/update/{id}', [ConsultaController::class, 'update']);
    Route::get('/show/{id}', [ConsultaController::class, 'show']);
});

//Servicio
Route::middleware('auth:sanctum')->prefix('servicio')->group(function () {
    Route::get('/index2', [ServiciosController::class, 'index2']);
    Route::get('/index', [ServiciosController::class, 'index']);
    Route::post('/store', [ServiciosController::class, 'store']);
    Route::patch('/update/{id}', [ServiciosController::class, 'update']);
    Route::post('/search', [ServiciosController::class, 'search']);
    Route::put('/cambiar-estado/{id}', [ServiciosController::class, 'cambiarEstado']);
    Route::delete('/delete/{id}', [ServiciosController::class, 'destroy']);
});


Route::prefix('trabajador')->group(function () {
    Route::get('/horarios', [TrabajadorController::class, 'getHorarios']); 
    Route::get('/trabajadores', [TrabajadorController::class, 'index']);
    Route::get('/trabajadores/{id}', [TrabajadorController::class, 'show']);
    Route::put('/trabajadores/{id}', [TrabajadorController::class, 'update']);
    Route::put('/trabajadores/{id}/horarios', [TrabajadorController::class, 'syncHorarios']);
    Route::delete('/trabajadores/{id}/desactivar', [TrabajadorController::class, 'desactivarTrabajador']);
    Route::put('/trabajadores/{id}/activar', [TrabajadorController::class, 'activarTrabajador']);
});