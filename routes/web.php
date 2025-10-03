<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionController;
use App\Http\Controllers\AccountController; 
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RuneController;
use App\Http\Controllers\SummonerSpellController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// TOPページ（企画詳細の通り、LoLのルール説明ページ）
Route::get('/', function () {
    return view('top');
})->name('top');

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

// TOPページ（企画詳細の通り、LoLのルール説明ページ）
Route::get('/', function () {
    return view('top');
})->name('top');

// チャンピオン関連のルートは省略...

// --- アカウント検索機能 ---
// アカウント検索フォームページを表示
Route::get('/account', [AccountController::class, 'search'])->name('account.search');

// アカウント検索を実行（POSTで受け取り、Puuidを取得してリダイレクト）
Route::post('/account/show', [AccountController::class, 'show'])->name('account.show');

// 対戦履歴詳細ページ（PuuidをURLパラメーターとして受け取る）
Route::get('/account/history/{puuid}/{start?}', [AccountController::class, 'history'])
    ->where('start', '[0-9]+') // startが数字であることを保証
    ->name('account.history');

// ▼▼▼【追記】対戦履歴をJSONデータとして取得するためのAPIルート ▼▼▼
Route::get('/api/matches/{puuid}/{start?}', [AccountController::class, 'fetchMatches'])->name('api.matches');

Route::get('/items', function () {
    return view('items');
})->name('items'); 

// 新しく/itemsルートをItemControllerに繋ぐ
Route::get('/items', [ItemController::class, 'index'])->name('items');
// アイテム詳細ページ (URLの{item}部分がIDとして渡される)
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// 検索履歴を削除するためのルート
Route::delete('/account/history/{history}', [AccountController::class, 'destroy'])->name('account.history.destroy');

// --- ルーンページ ---
Route::get('/runes', [RuneController::class, 'index'])->name('runes.index');
Route::get('/runes/{pathId}', [RuneController::class, 'show'])->name('runes.show');

// --- サモナースペル ---
Route::get('/spells', [SummonerSpellController::class, 'index'])->name('spells.index');
Route::get('/spells/{spell}', [SummonerSpellController::class, 'show'])->name('spells.show');