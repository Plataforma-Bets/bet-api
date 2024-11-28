<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\CampeonatosController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/ligas', [CampeonatosController::class, 'listarTodasLigas']);
Route::get('/ligas/pais', [CampeonatosController::class, 'listarLigasPorPais']);

Route::get('/times', [TimeController::class, 'buscarTimes']);

Route::get('/partidas', [PartidaController::class, 'buscarPartidas']);
Route::get('/partidas/por-time', [PartidaController::class, 'listarPartidasPorTime']);

Route::post('/salvar-logos', [TimeController::class, 'salvarLogos']);
Route::post('/salvar-partidas', [PartidaController::class, 'store']);
