<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SearchHistory; // 忘れずにuse

class AccountController extends Controller
{
    /**
     * アカウント検索フォームと履歴一覧を表示する
     */
    public function search()
    {
        // データベースから検索履歴を新しい順に取得
        $histories = SearchHistory::latest()->get();

        // 取得した履歴をビューに渡す
        return view('account.search', compact('histories'));
    }

    /**
     * アカウント検索を実行し、結果を保存して詳細ページにリダイレクトする
     */
    public function show(Request $request)
    {
        $gameName = $request->input('gameName');
        $tagLine = $request->input('tagLine');
        $apiKey = env('RIOT_API_KEY');

        $tagLine = ltrim($tagLine, '#');

        $response = Http::withHeaders([
            'X-Riot-Token' => $apiKey
        ])->get("https://asia.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}");

        if ($response->successful()) {
            $puuid = $response->json()['puuid'];

            // 既に同じプレイヤーが履歴に存在する場合、古いものを削除 (リストの先頭に移動させるため)
            SearchHistory::where('puuid', $puuid)->delete();

            // 新しい履歴としてデータベースに保存
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

    /**
     * 検索履歴を1件削除する
     */
    public function destroy(SearchHistory $history)
    {
        $history->delete();
        return back()->with('success', '検索履歴から削除しました。');
    }
    
    /**
     * 試合履歴やランク情報を表示する（このままでは機能しません。対戦履歴取得ロジックは別途必要）
     * 仮の対戦履歴表示ロジックをここに記述します。
     */
    public function history($puuid)
    {
        $apiKey = env('RIOT_API_KEY');
        
        // 2. Match APIを使って試合IDのリストを取得 (ASIAリージョンを使用)
        // start=0, count=10 で最新10件を取得
        $matchIdResponse = Http::withHeaders([
            'X-Riot-Token' => $apiKey
        ])->get("https://asia.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids?start=0&count=10");

        if (!$matchIdResponse->successful()) {
            return back()->withErrors(['historyError' => '試合履歴の取得に失敗しました。']);
        }

        $matchIds = $matchIdResponse->json();
        $matches = [];
        
        // 試合詳細を取得するロジック（時間がかかるため、実運用ではキャッシュ推奨）
        foreach ($matchIds as $matchId) {
            $matchResponse = Http::withHeaders([
                'X-Riot-Token' => $apiKey
            ])->get("https://asia.api.riotgames.com/lol/match/v5/matches/{$matchId}");

            if ($matchResponse->successful()) {
                $matches[] = $matchResponse->json();
            }
        }
        
        // 3. Summoner APIでランク情報を取得するためにSummonerIdを取得（EUROPEリージョンは使えないため、適当なリージョンで再取得が必要）
        // ここでは簡単な表示のため、割愛します。
        // ...
        
        // ビューにデータを渡す
        return view('account.history', compact('matches', 'puuid'));
    }
}