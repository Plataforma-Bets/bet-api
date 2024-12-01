<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\CampeonatosController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/ligas', [CampeonatosController::class, 'listarTodasLigas']);
    Route::get('/ligas/pais', [CampeonatosController::class, 'listarLigasPorPais']);

    Route::get('/times', [TimeController::class, 'buscarTimes']);
    Route::get('/times/{id}', [TimeController::class, 'buscarTimePorId']);
    
    Route::get('/partidas', [PartidaController::class, 'buscarPartidas']);
    Route::get('/partidas/time', [PartidaController::class, 'listarPartidasPorTime']);

    #Web-scraping
    Route::post('/salvar-logos', [TimeController::class, 'salvarLogos']);
});
Route::post('/salvar-partidas', [PartidaController::class, 'salvarPartidas']);
Route::post('/scrape-flashscore', function () {
    $output = null;
    $resultCode = null;

    exec('node ' . base_path('scripts/scrape-flashscore.js'), $output, $resultCode);

    if ($resultCode === 0) {
        return response()->json(['message' => 'Scraping realizado com sucesso!'], 200);
    } else {
        return response()->json(['message' => 'Erro ao rodar o scraping.'], 500);
    }
});