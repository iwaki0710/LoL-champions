<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;

class AccountController extends Controller
{
    public function search()
    {
        $histories = SearchHistory::latest()->get();
        return view('account.search', compact('histories'));
    }

    public function show(Request $request)
    {
        $gameName = $request->input('gameName');
        $tagLine = $request->input('tagLine');
        $apiKey = env('RIOT_API_KEY');
        $tagLine = ltrim($tagLine, '#');

        $response = Http::withHeaders(['X-Riot-Token' => $apiKey])
            ->get("https://asia.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}");

        if ($response->successful()) {
            $puuid = $response->json()['puuid'];
            SearchHistory::where('puuid', $puuid)->delete();
            SearchHistory::create([
                'gameName' => $gameName,
                'tagLine' => $tagLine,
                'puuid' => $puuid,
            ]);
            return redirect()->route('account.history', ['puuid' => $puuid]);
        } else {
            return back()->withErrors(['searchError' => 'アカウントが見つかりませんでした。']);
        }
    }

    public function destroy(SearchHistory $history)
    {
        $history->delete();
        return back()->with('success', '検索履歴から削除しました。');
    }

    /**
     * 対戦履歴の詳細情報を表示する
     */
    public function history($puuid)
    {
        $apiKey = env('RIOT_API_KEY');
        
        // Data Dragonの最新バージョンを取得 (画像URLのため)
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];

        // 試合IDのリストを取得
        $matchIdResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
            ->get("https://asia.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids?start=0&count=10");

        if (!$matchIdResponse->successful()) {
            return back()->withErrors(['historyError' => '試合履歴の取得に失敗しました。']);
        }

        $matchIds = $matchIdResponse->json();
        $processedMatches = []; // ビューに渡すための整形済み試合データ配列

        foreach ($matchIds as $matchId) {
            $matchResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
                ->get("https://asia.api.riotgames.com/lol/match/v5/matches/{$matchId}");

            if ($matchResponse->successful()) {
                $matchData = $matchResponse->json();
                $playerData = null;

                // 自分のプレイヤーデータを検索
                foreach ($matchData['info']['participants'] as $participant) {
                    if ($participant['puuid'] === $puuid) {
                        $playerData = $participant;
                        break;
                    }
                }

                if ($playerData) {
                    // 対面のプレイヤーを検索
                    $opponentData = null;
                    foreach ($matchData['info']['participants'] as $participant) {
                        // チームが異なり、かつレーンポジションが同じプレイヤーを探す
                        if ($participant['teamId'] !== $playerData['teamId'] && $participant['individualPosition'] === $playerData['individualPosition']) {
                            $opponentData = $participant;
                            break;
                        }
                    }

                    // プレイヤーが使用したアイテムのIDリストを作成 (0は空スロットなので除外)
                    $items = [];
                    for ($i = 0; $i < 7; $i++) {
                        if ($playerData['item' . $i] !== 0) {
                            $items[] = $playerData['item' . $i];
                        }
                    }
                    
                    // 試合データをビューで使いやすい形に整理
                    $processedMatches[] = [
                        'win' => $playerData['win'],
                        'championName' => $playerData['championName'],
                        'kills' => $playerData['kills'],
                        'deaths' => $playerData['deaths'],
                        'assists' => $playerData['assists'],
                        'items' => $items,
                        'gameMode' => $this->getQueueName($matchData['info']['queueId']),
                        'gameDuration' => $matchData['info']['gameDuration'],
                        'opponentChampionName' => $opponentData['championName'] ?? null, 
                    ];
                }
            }
        }
        
        return view('account.history', compact('processedMatches', 'puuid', 'version'));
    }

    /**
     * Queue IDから日本語のゲームモード名を取得するヘルパー関数
     */
    private function getQueueName(int $queueId): string
    {
        return match ($queueId) {
            400 => 'ノーマル (ドラフト)',
            420 => 'ランク (ソロ/デュオ)',
            430 => 'ノーマル (ブラインド)',
            440 => 'ランク (フレックス)',
            450 => 'ARAM',
            700 => 'Clash',
            default => '特殊モード',
        };
    }
}
