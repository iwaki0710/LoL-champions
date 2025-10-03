<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $spell->name }} - サモナースペル詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="spell-detail-container">
        <div class="spell-image-section">
            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $spell->image_full }}" alt="{{ $spell->name }}">
        </div>
        <div class="spell-info-section">
            <h1>{{ $spell->name }}</h1>
            <p class="spell-cooldown">クールダウン: {{ $spell->cooldown }}秒</p>
            <p class="spell-description">{{ $spell->description }}</p>
        </div>
    </div>
</body>
</html>