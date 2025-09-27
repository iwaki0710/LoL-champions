<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>LoL チャンピオン一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <x-lane-icons />
    <x-filter-form :difficultyFilter="$difficultyFilter" :roleFilter="$roleFilter" />

    <hr>
    
    <x-champions-list :filteredChampions="$filteredChampions" :version="$version" />

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>