<?php

use App\Http\Controllers\Autenticacao\AutenticacaoController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Externo\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('api/documentacao');
});


Route::controller(AutenticacaoController::class)->group(function () {
    Route::post('registro', 'registro');
    Route::post('login', 'login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout');
        Route::get('user', static function (Request $request) {
            return $request->user();
        });
    });
});

Route::middleware('auth:sanctum')->controller(DashboardController::class)->group(function () {
    Route::get('dashboard','index');
});


Route::middleware('auth:sanctum')->controller(WeatherController::class)->group(function () {
    Route::get('weather','buscaDadosMeteorologicos');
    Route::get('weather/historico', 'buscaHistorico');
    Route::get('weather/historico/{codHistorico}', 'detalhesHistorico');
});
