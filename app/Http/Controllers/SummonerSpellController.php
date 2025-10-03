<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummonerSpell;
use Illuminate\Support\Facades\Http;

class SummonerSpellController extends Controller
{
    private function getLatestVersion()
    {
        $versionsResponse = Http::get("https://ddragon.leagueoflegends.com/api/versions.json");
        return $versionsResponse->json()[0];
    }

    public function index()
    {
        $spells = SummonerSpell::all(); 
        $version = $this->getLatestVersion();
        return view('spells.index', compact('spells', 'version'));
    }

    public function show(SummonerSpell $spell)
    {
        $version = $this->getLatestVersion();
        return view('spells.show', compact('spell', 'version'));
    }
}