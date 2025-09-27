<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->truncate();
        $locale = 'ja_JP';
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];
        $url = "https://ddragon.leagueoflegends.com/cdn/{$version}/data/{$locale}/item.json";
        $response = Http::get($url);

        if ($response->successful()) {
            $itemsData = $response->json()['data'];
            $itemsToInsert = [];

            foreach ($itemsData as $itemId => $item) {
                // サモナーズリフトで利用可能なアイテムのみを対象とする
                if ($item['maps']['11']) {
                    $itemsToInsert[] = [
                        'id' => $itemId,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'plaintext' => $item['plaintext'] ?? '',
                        'total_gold' => $item['gold']['total'],
                        'tags' => json_encode($item['tags']),
                        'builds_into' => isset($item['into']) ? json_encode($item['into']) : null,
                        'purchasable' => $item['gold']['purchasable'],
                        'builds_from' => isset($item['from']) ? json_encode($item['from']) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            DB::table('items')->insert($itemsToInsert);
            $this->command->info('Item data seeded successfully!');
        } else {
            $this->command->error("アイテムデータを取得できませんでした。URL: {$url}");
        }
    }
}

