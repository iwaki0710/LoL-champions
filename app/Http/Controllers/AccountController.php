<?php

namespace App\Http\Controllers;

use App\Models\Rune;
use App\Models\SummonerSpell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory;

class AccountController extends Controller
{
    /**
     * アカウント検索ページを表示
     */
    public function search()
    {
        $histories = SearchHistory::latest()->get();
        return view('account.search', compact('histories'));
    }

    /**
     * アカウントを検索し、PUUIDを取得して対戦履歴ページにリダイレクト
     */
    public function show(Request $request)
    {
        $gameName = $request->input('gameName');
        $tagLine = $request->input('tagLine');
        $apiKey = env('RIOT_API_KEY');
        $tagLine = ltrim($tagLine, '#');

        $apiUrl = "https://asia.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}";

        $response = Http::withHeaders(['X-Riot-Token' => $apiKey])->get($apiUrl);

        if ($response->successful()) {
            $puuid = $response->json()['puuid'];
            SearchHistory::where('puuid', $puuid)->delete();
            SearchHistory::create([
                'gameName' => $gameName,
                'tagLine' => $tagLine,
                'puuid' => $puuid,
            ]);
            // platformを渡さないシンプルなルートに戻す
            return redirect()->route('account.history', ['puuid' => $puuid]);
        } else {
            $statusCode = $response->status();
            $errorMessage = "アカウントが見つかりませんでした。 Riot IDとタグが正しいか確認してください。";

            if ($statusCode === 401 || $statusCode === 403) {
                $errorMessage = "Riot APIキーが無効か、有効期限が切れています。開発者ポータルでキーを再生成してください。";
            } elseif ($statusCode === 404) {
                $errorMessage = "指定されたアカウントが見つかりませんでした。Riot IDとタグを確認してください。";
            } elseif ($statusCode >= 500) {
                $errorMessage = "Riot Gamesのサーバー側で問題が発生しているようです。時間をおいて再度お試しください。";
            }

            return back()->withErrors(['searchError' => $errorMessage]);
        }
    }

    /**
     * 検索履歴を削除
     */
    public function destroy(SearchHistory $history)
    {
        $history->delete();
        return back()->with('success', '検索履歴から削除しました。');
    }

    /**
     * 対戦履歴の詳細情報を表示する
     */
    public function history($puuid, $start = 0)
    {
        $apiKey = env('RIOT_API_KEY');
        
        $summonerSpells = SummonerSpell::all()->keyBy('key');
        $runes = Rune::all()->keyBy('id');

        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];

        $matchIdResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
            ->get("https://asia.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids?start={$start}&count=10");

        if (!$matchIdResponse->successful()) {
            return back()->withErrors(['searchError' => '試合履歴の取得に失敗しました。']);
        }

        $matchIds = $matchIdResponse->json();
        $processedMatches = [];

        foreach ($matchIds as $matchId) {
            $matchResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
                ->get("https://asia.api.riotgames.com/lol/match/v5/matches/{$matchId}");

            if ($matchResponse->successful()) {
                $matchData = $matchResponse->json();
                $playerData = null;

                foreach ($matchData['info']['participants'] as $participant) {
                    if ($participant['puuid'] === $puuid) {
                        $playerData = $participant;
                        break;
                    }
                }

                if ($playerData) {
                    $deaths = $playerData['deaths'] == 0 ? 1 : $playerData['deaths'];
                    $kda = round(($playerData['kills'] + $playerData['assists']) / $deaths, 2);

                    $totalCS = $playerData['totalMinionsKilled'] + $playerData['neutralMinionsKilled'];
                    
                    $gameDurationMinutes = $matchData['info']['gameDuration'] / 60;
                    $csPerMinute = $gameDurationMinutes > 0 ? round($totalCS / $gameDurationMinutes, 1) : 0;
                    
                    $spell1 = $summonerSpells->get($playerData['summoner1Id']);
                    $spell2 = $summonerSpells->get($playerData['summoner2Id']);

                    $keystone = $runes->get($playerData['perks']['styles'][0]['selections'][0]['perk']);
                    $subStyle = $runes->get($playerData['perks']['styles'][1]['style']);
                    
                    $opponentData = null;
                    foreach ($matchData['info']['participants'] as $participant) {
                        if ($participant['teamId'] !== $playerData['teamId'] && $participant['individualPosition'] === $playerData['individualPosition']) {
                            $opponentData = $participant;
                            break;
                        }
                    }

                    $items = [];
                    for ($i = 0; $i < 7; $i++) {
                        if ($playerData['item' . $i] !== 0) {
                            $items[] = $playerData['item' . $i];
                        }
                    }
                    
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
                        'spell1' => $spell1,
                        'spell2' => $spell2,
                        'keystoneRune' => $keystone,
                        'subRuneStyle' => $subStyle,
                        'kda' => $kda,
                        'totalCS' => $totalCS,
                        'csPerMinute' => $csPerMinute,
                    ];
                }
            }
        }
        
        $nextStart = $start + 10;
        
        return view('account.history', compact('processedMatches', 'puuid', 'version', 'nextStart'));
    }

    /**
     * 対戦履歴のデータをJSON形式で取得する (AJAX用)
     */
    public function fetchMatches($puuid, $start = 0)
    {
        $apiKey = env('RIOT_API_KEY');
        
        $summonerSpells = SummonerSpell::all()->keyBy('key');
        $runes = Rune::all()->keyBy('id');

        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];

        $matchIdResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
            ->get("https://asia.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids?start={$start}&count=10");

        if (!$matchIdResponse->successful()) {
            return response()->json(['error' => '試合履歴の取得に失敗しました。'], 500);
        }

        $matchIds = $matchIdResponse->json();
        $processedMatches = [];

        foreach ($matchIds as $matchId) {
            $matchResponse = Http::withHeaders(['X-Riot-Token' => $apiKey])
                ->get("https://asia.api.riotgames.com/lol/match/v5/matches/{$matchId}");

            if ($matchResponse->successful()) {
                $matchData = $matchResponse->json();
                $playerData = null;
                foreach ($matchData['info']['participants'] as $participant) {
                    if ($participant['puuid'] === $puuid) {
                        $playerData = $participant;
                        break;
                    }
                }

                if ($playerData) {
                    $deaths = $playerData['deaths'] == 0 ? 1 : $playerData['deaths'];
                    $kda = round(($playerData['kills'] + $playerData['assists']) / $deaths, 2);
                    $totalCS = $playerData['totalMinionsKilled'] + $playerData['neutralMinionsKilled'];
                    $gameDurationMinutes = $matchData['info']['gameDuration'] / 60;
                    $csPerMinute = $gameDurationMinutes > 0 ? round($totalCS / $gameDurationMinutes, 1) : 0;
                    $spell1 = $summonerSpells->get($playerData['summoner1Id']);
                    $spell2 = $summonerSpells->get($playerData['summoner2Id']);
                    $keystone = $runes->get($playerData['perks']['styles'][0]['selections'][0]['perk']);
                    $subStyle = $runes->get($playerData['perks']['styles'][1]['style']);
                    
                    $opponentData = null;
                    foreach ($matchData['info']['participants'] as $p) {
                        if ($p['teamId'] !== $playerData['teamId'] && $p['individualPosition'] === $playerData['individualPosition']) {
                            $opponentData = $p;
                            break;
                        }
                    }

                    $items = [];
                    for ($i = 0; $i < 7; $i++) {
                        if ($playerData['item' . $i] !== 0) {
                            $items[] = $playerData['item' . $i];
                        }
                    }

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
                        'spell1_image' => $spell1 ? $spell1->image_full : null,
                        'spell2_image' => $spell2 ? $spell2->image_full : null,
                        'keystone_icon' => $keystone ? strtolower($keystone->icon_path) : null,
                        'substyle_icon' => $subStyle ? strtolower($subStyle->icon_path) : null,
                        'kda' => $kda,
                        'totalCS' => $totalCS,
                        'csPerMinute' => $csPerMinute,
                    ];
                }
            }
        }
        
        // データをJSON形式で返す
        return response()->json([
            'matches' => $processedMatches,
            'version' => $version,
        ]);
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