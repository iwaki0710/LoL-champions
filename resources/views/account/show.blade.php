<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $summonerData['name'] }}のアカウント詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <h1>{{ $summonerData['name'] }}</h1>
    <div class="summoner-details">
        <p>サモナーレベル: {{ $summonerData['summonerLevel'] }}</p>
        <p>アカウントID: {{ $summonerData['accountId'] }}</p>
        <p>PUUID: {{ $summonerData['puuid'] }}</p>
        {{-- ここに対戦履歴やランク情報を表示します --}}
    </div>
</body>
</html>