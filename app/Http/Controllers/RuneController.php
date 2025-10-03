<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rune;

class RuneController extends Controller
{

    public function index()
    {
        $paths = Rune::where('slot_index', -1)->get();
        return view('runes.index', compact('paths'));
    }


    public function show($pathId)
    {
        if ($pathId === 'StatMods') {
            return view('runes.shards');
        }

        $path = Rune::findOrFail($pathId);
        $runes = Rune::where('path_id', $pathId)
                    ->where('slot_index', '!=', -1) 
                    ->orderBy('slot_index')
                    ->get();

        $groupedRunes = $runes->groupBy('slot_index');

        $keystones = $groupedRunes->pull(0) ?? collect(); 
        $otherRunes = $groupedRunes;

        return view('runes.show', compact('path', 'keystones', 'otherRunes'));
    }
}