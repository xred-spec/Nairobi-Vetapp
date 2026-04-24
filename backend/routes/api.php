<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(base_path('routes/routes_version/v1.php'));

Route::get('test', function(){
    return response()->json(['message' => now()]);
});

Route::get('/test-cron', function () {
    \Artisan::call('schedule:run');
    // return 'cron ejecutado: ' . now();
    return response()->json(['message' => now()]);
});