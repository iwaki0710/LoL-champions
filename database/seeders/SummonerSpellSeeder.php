<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\SummonerSpell;

class SummonerSpellSeeder extends Seeder
{
    public function run(): void
    {
        SummonerSpell::truncate();

        $locale = 'ja_JP';
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];
        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/summoner.json";

        $response = Http::get($url);
        if ($response->successful()) {
            $spellsData = $response->json()['data'];
            $spellsToInsert = [];

            foreach ($spellsData as $spell) {
                    $spellsToInsert[] = [
                    'id' => $spell['id'],
                    'name' => $spell['name'],
                    'description' => $spell['description'],
                    'cooldown' => $spell['cooldown'][0],
                    'key' => $spell['key'],
                    'image_full' => $spell['image']['full'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // 配列にまとめたデータを一度にDBに保存
            SummonerSpell::insert($spellsToInsert);
            
            $this->command->info('Summoner Spells seeded successfully!');
        } else {
            $this->command->error('Failed to fetch Summoner Spells.');
        }
    }
}