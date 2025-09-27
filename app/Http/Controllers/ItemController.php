<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Http;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $sub_filter = $request->query('sub_filter');
        $query = Item::query();
        
        $starterItemIds = [
            '1054', '1055', '1056', '1082', '3865', '2141',
        ];
        
        $specialExistingItemIds = [
            '3040', '323040', '3042', '323042', '3121', '323121',
            '2422', '3866', '3867',
        ];
        
        $categoryTags = ['Boots', 'Consumable', 'Trinket', 'Vision', 'Jungle', 'Starting'];

        if ($filter === 'deleted') {
            $query->where('purchasable', false)
                  ->whereNotIn('id', $specialExistingItemIds);
        } else {
            $query->where(function ($q) use ($specialExistingItemIds) {
                $q->where('purchasable', true)
                  ->orWhereIn('id', $specialExistingItemIds);
            });

            if ($filter === 'category') {
                if ($sub_filter) {
                    if ($sub_filter === 'Vision') {
                        $query->where(function ($q) {
                            $q->whereJsonContains('tags', 'Vision')
                              ->orWhereJsonContains('tags', 'Trinket');
                        });
                    } 
                    elseif ($sub_filter === 'Jungle') {
                        $query->where(function ($q) use ($starterItemIds) {
                            $q->whereJsonContains('tags', 'Jungle')
                              ->orWhereJsonContains('tags', 'Starting')
                              ->orWhereIn('id', $starterItemIds);
                        });
                    }
                    else {
                        $query->whereJsonContains('tags', $sub_filter);
                    }
                } else {
                    $query->where(function ($q) use ($categoryTags, $starterItemIds) {
                        $q->whereIn('id', $starterItemIds); 
                        foreach ($categoryTags as $tag) {
                            $q->orWhereJsonContains('tags', $tag);
                        }
                    });
                }
            }
            elseif ($filter === 'completed') {
                $query->whereNull('builds_into');
                $query->whereNotIn('id', $starterItemIds);
                $query->where(function ($q) use ($categoryTags) {
                    foreach ($categoryTags as $tag) {
                         $q->whereJsonDoesntContain('tags', $tag);
                    }
                });
            }
        }

        $items = $query->groupBy('name')->get();

        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];

        return view('items.index', compact('items', 'version', 'filter', 'sub_filter'));
    }

    public function show(Item $item)
    {
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        $version = $versionsResponse->json()[0];

        $fromIds = json_decode($item->builds_from) ?? [];
        
        $fromItems = Item::whereIn('id', $fromIds)->get();

        return view('items.show', compact('item', 'version', 'fromItems'));
    }
}
