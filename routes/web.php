<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('champions.index');
});

// チャンピオン一覧（全キャラクター）
Route::get('/champions', [ChampionController::class, 'index'])->name('champions.index');

// 各レーンごとのチャンピオン一覧
Route::get('/champions/top', [ChampionController::class, 'top'])->name('champions.top');
Route::get('/champions/jungle', [ChampionController::class, 'jungle'])->name('champions.jungle');
Route::get('/champions/middle', [ChampionController::class, 'middle'])->name('champions.middle');
Route::get('/champions/bottom', [ChampionController::class, 'bottom'])->name('champions.bottom');
Route::get('/champions/support', [ChampionController::class, 'support'])->name('champions.support');

// チャンピオン詳細ページ
Route::get('/champions/{champion}', [ChampionController::class, 'show'])->name('champions.show');