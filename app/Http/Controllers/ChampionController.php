<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Champion;

class ChampionController extends Controller
{
    /**
     * すべてのチャンピオン情報を表示する（トップページ）
     */
    public function index(Request $request)
    {
        $query = Champion::query();
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request);
        return view('champions.index', $viewModel);
    }

    /**
     * Topレーンのチャンピオン一覧を表示する
     */
    public function top(Request $request)
    {
        $query = Champion::where('lane', 'Top');
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request, 'Top');
        return view('champions.lane', $viewModel);
    }

    /**
     * Jungleレーンのチャンピオン一覧を表示する
     */
    public function jungle(Request $request)
    {
        $query = Champion::where('lane', 'Jungle');
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request, 'Jungle');
        return view('champions.lane', $viewModel);
    }

    /**
     * Middleレーンのチャンピオン一覧を表示する
     */
    public function middle(Request $request)
    {
        $query = Champion::where('lane', 'Middle');
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request, 'Middle');
        return view('champions.lane', $viewModel);
    }

    /**
     * Bottomレーンのチャンピオン一覧を表示する
     */
    public function bottom(Request $request)
    {
        $query = Champion::where('lane', 'Bottom');
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request, 'Bottom');
        return view('champions.lane', $viewModel);
    }

    /**
     * Supportレーンのチャンピオン一覧を表示する
     */
    public function support(Request $request)
    {
        $query = Champion::where('lane', 'Support');
        $filteredChampions = $this->applyFilters($query, $request)->get();
        $viewModel = $this->getViewModel($filteredChampions, $request, 'Support');
        return view('champions.lane', $viewModel);
    }

    /**
     * チャンピオン詳細情報を表示する
     */
    public function show($championName)
    {
        $champion = Champion::where('id', $championName)->firstOrFail();
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $versions = $versionsResponse->json();
        $version = $versions[0];
        $locale = 'ja_JP';
        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/champion/{$championName}.json";
        $response = Http::get($url);

        if ($response->successful()) {
            $championApiData = $response->json()['data'][$championName];
            $champion->info_api = $championApiData['info'];
            $champion->passive = $championApiData['passive'];
            $champion->spells = $championApiData['spells'];
            $champion->image = $championApiData['image'];

            return view('champions.show', compact('champion', 'version'));
        } else {
            return back()->with('error', 'APIからチャンピオン詳細情報を取得できませんでした。');
        }
    }

    /**
     * 難易度とロールのフィルタリングを適用するプライベートメソッド
     */
    private function applyFilters($query, Request $request)
    {
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
        
        $roleFilter = $request->query('role');
        if ($roleFilter) {
            $query->whereJsonContains('tags', $roleFilter);
        }
        
        return $query;
    }

    /**
     * ビューに渡す共通データを取得するプライベートメソッド
     */
    private function getViewModel($filteredChampions, Request $request, $laneFilter = null)
    {
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $versions = $versionsResponse->json();
        $version = $versions[0];

        $roleFilter = $request->query('role');
        $difficultyFilter = $request->query('difficulty');

        return compact('filteredChampions', 'roleFilter', 'difficultyFilter', 'laneFilter', 'version');
    }
}