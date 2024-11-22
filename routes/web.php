<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashscoreController;

Route::get('/scrape-flashscore', [FlashscoreController::class, 'scrapeFlashscore']);
Route::get('/', function () {
    return view('welcome');
});
