<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionController;

// チャンピオン一覧ページへのルート
Route::get('/champions', [ChampionController::class, 'index'])->name('champions.index');

// 各レーンごとのチャンピオン一覧
Route::get('/champions/top', [ChampionController::class, 'top'])->name('champions.top');
Route::get('/champions/jungle', [ChampionController::class, 'jungle'])->name('champions.jungle');
Route::get('/champions/middle', [ChampionController::class, 'middle'])->name('champions.middle');
Route::get('/champions/bottom', [ChampionController::class, 'bottom'])->name('champions.bottom');
Route::get('/champions/support', [ChampionController::class, 'support'])->name('champions.support');

//チャンピオン詳細ページへのルート
Route::get('/champions/{champion}',[ChampionController::class, 'show'])->name('champions.show');