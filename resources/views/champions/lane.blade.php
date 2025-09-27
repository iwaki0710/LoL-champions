<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>LoL {{ $laneFilter ?? '全' }}レーン チャンピオン一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />
    <h1 class="lol">{{ $laneFilter ?? '' }}レーン チャンピオン一覧</h1>

    <div class="explanation-box">
        <div class="explanation">
            <h2 class="explanation-h2">{{ $laneFilter ?? '' }}レーンの特徴</h2>
            {{-- ここで対応するBladeファイルを読み込む --}}
            @include('explanations.' . (strtolower($laneFilter) ?? 'default'))
        </div>
    </div>
<hr>

<x-lane-icons />

<x-filter-form :difficultyFilter="$difficultyFilter" :roleFilter="$roleFilter" />

<hr>

<x-champions-list :filteredChampions="$filteredChampions" :version="$version" />
</body>
</html>