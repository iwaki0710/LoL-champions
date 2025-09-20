<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChampionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        DB::table('champions')->truncate();

        $locale = 'ja_JP';
        
        // Riot API から最新バージョン一覧を取得
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $versions = $versionsResponse->json();
        
        if (empty($versions)) {
            $this->command->error('APIからバージョン情報を取得できませんでした。');
            return;
        }

        $version = $versions[0]; // 最新版を取得

        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/champion.json";
        $response = Http::get($url);

        if ($response->successful()) {
            $championsData = $response->json()['data'];
            $championsToInsert = [];

            foreach ($championsData as $champion) {
                $championsToInsert[] = [
                    'id' => $champion['id'],
                    'name' => $champion['name'],
                    'title' => $champion['title'],
                    'blurb' => $champion['blurb'],
                    'difficulty' => $champion['info']['difficulty'],
                    'tags' => json_encode($champion['tags']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // データベースに一括挿入
            DB::table('champions')->insert($championsToInsert);
        } else {
            $this->command->error("チャンピオンデータを取得できませんでした。URL: {$url}");
        }
    }
}