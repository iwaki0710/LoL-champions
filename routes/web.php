<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionController;

// チャンピオン一覧ページへのルート
Route::get('/champions', [ChampionController::class, 'index']);

//チャンピオン詳細ページへのルート
Route::get('/champions/{champion}',[ChampionController::class, 'show']);