<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サモナースペル一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />
    <h1 style="text-align: center;">サモナースペル</h1>

    <div class="spells-container">
        @foreach ($spells as $spell)
            <a href="{{ route('spells.show', $spell->id) }}" class="spell-card">
                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $spell->image_full }}" alt="{{ $spell->name }}">
                <p>{{ $spell->name }}</p>
            </a>
        @endforeach
    </div>
</body>
</html>