<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $champion->name }}の詳細</title>
</head>
<body>
<h1>{{ $champion->name }} - {{ $champion->title }}</h1>
    <p>{{ $champion->blurb }}</p>

    {{-- 難易度（データベースから取得） --}}
    <p>難易度: 
        @php
            $difficulty = $champion->difficulty;
            if ($difficulty >= 0 && $difficulty <= 3) {
                $stars = 1;
            } elseif ($difficulty >= 4 && $difficulty <= 7) {
                $stars = 2;
            } else {
                $stars = 3;
            }
        @endphp
        @for ($i = 0; $i < $stars; $i++)
            <span>★</span>
        @endfor
    </p>

    {{-- パッシブ情報（APIから取得） --}}
    @if (isset($champion->passive))
        <h2>パッシブ</h2>
        <h3>{{ $champion->passive['name'] }}</h3>
        <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/passive/{{ $champion->passive['image']['full'] }}" alt="パッシブアイコン" width="50">
        <p>{!! $champion->passive['description'] !!}</p>
        <hr>
    @endif

    {{-- スキル情報（APIから取得） --}}
    @if (isset($champion->spells))
        <h2>スキル</h2>
        @foreach ($champion->spells as $spell)
            <h3>{{ $spell['name'] }}</h3>
            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $spell['image']['full'] }}" alt="{{ $spell['name'] }}アイコン" width="50">
            <p>{!! $spell['description'] !!}</p>
            <p>クールダウン: {{ implode(', ', $spell['cooldown']) }}秒</p>
            <hr>
        @endforeach
    @endif

</body>
</html>