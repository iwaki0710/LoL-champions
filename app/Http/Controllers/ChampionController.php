<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Champion; // Championモデルをインポート

class ChampionController extends Controller
{
    /**
     * すべてのチャンピオン情報をデータベースから取得し、フィルタリングして表示する
     */
    public function index(Request $request)
    {
        // データベースからすべてのチャンピオン情報を取得
        $query = Champion::query();

        // 難易度によるフィルタリング
        $difficultyFilter = $request->query('difficulty');
        if ($difficultyFilter) {
            if ($difficultyFilter === 'star1') {
                $query->whereBetween('difficulty', [0, 3]);
            } elseif ($difficultyFilter === 'star2') {
                $query->whereBetween('difficulty', [4, 7]);
            } elseif ($difficultyFilter === 'star3') {
                $query->whereBetween('difficulty', [8, 10]);
            }
        }
        
        // ロールによるフィルタリング
        $roleFilter = $request->query('role');
        if ($roleFilter) {
            $query->whereJsonContains('tags', $roleFilter);
        }
        
        // レーンによるフィルタリング
        $laneFilter = $request->query('lane');
        if ($laneFilter) {
            $query->where('lane', $laneFilter);
        }

        // フィルタリングされたデータを取得
        $filteredChampions = $query->get();
        
        // 最新のAPIバージョンを取得してビューに渡す
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $versions = $versionsResponse->json();
        $version = $versions[0];

        // ビューに渡す
        return view('champions.index', compact('filteredChampions', 'roleFilter', 'difficultyFilter', 'laneFilter', 'version'));
    }

    /**
     * 特定のチャンピオンの詳細情報を取得して表示する
     */
    public function show($championName)
    {
        // 1. データベースからチャンピオンの基本情報を取得
        $champion = Champion::where('id', $championName)->firstOrFail();

        // 2. 最新のAPIバージョンを取得
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $versions = $versionsResponse->json();
        $version = $versions[0];
        $locale = 'ja_JP';
        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/champion/{$championName}.json";
        $response = Http::get($url);

        if ($response->successful()) {
            $championApiData = $response->json()['data'][$championName];
            
            // データベースの$championオブジェクトにAPIの情報を追加
            $champion->info_api = $championApiData['info'];
            $champion->passive = $championApiData['passive'];
            $champion->spells = $championApiData['spells'];
            $champion->image = $championApiData['image'];

            return view('champions.show', compact('champion', 'version'));
        } else {
            // API取得に失敗した場合
            return view('champions.show', compact('champion', 'version'));
        }
    }
}