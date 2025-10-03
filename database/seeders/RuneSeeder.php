<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Rune;

class RuneSeeder extends Seeder
{
    public function run(): void
    {
        Rune::truncate();
        
        $locale = 'ja_JP';
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];
        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/runesReforged.json";
        
        $response = Http::get($url);

        if ($response->successful()) {
            $runePaths = $response->json();
            $runesToInsert = [];

            foreach ($runePaths as $path) {
                // パス自体の情報を保存
                $runesToInsert[] = [
                    'id' => $path['id'],
                    'key' => $path['key'],
                    'icon_path' => $path['icon'],
                    'name' => $path['name'],
                    'short_desc' => '', // パス自体には説明がない
                    'long_desc' => '',
                    'path_id' => $path['id'], // 自分自身を指す
                    'slot_index' => -1, // パス自体はスロットに属さない
                    'is_keystone' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 各スロットのルーン情報を保存
                foreach ($path['slots'] as $slotIndex => $slot) {
                    foreach ($slot['runes'] as $rune) {
                        $runesToInsert[] = [
                            'id' => $rune['id'],
                            'key' => $rune['key'],
                            'icon_path' => $rune['icon'],
                            'name' => $rune['name'],
                            'short_desc' => $rune['shortDesc'],
                            'long_desc' => $rune['longDesc'],
                            'path_id' => $path['id'],
                            'slot_index' => $slotIndex,
                            'is_keystone' => $slotIndex === 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            DB::table('runes')->insert($runesToInsert);
            $this->command->info('Rune data seeded successfully!');
        } else {
            $this->command->error("ルーンデータを取得できませんでした。");
        }
    }
}