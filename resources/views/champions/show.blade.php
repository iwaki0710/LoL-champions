<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $champion->name }}の詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">  
</head>
<body>
    <div class="detail-champ">
        <div class="champ-title">
            <h1>{{ $champion->name }} - {{ $champion->title }}</h1>
            <p>{{ $champion->blurb }}</p>
        </div>
        {{-- スプラッシュアートとスキンの表示 --}}
        @if (isset($championApiData['skins']))
            <div class="splash-container">
                {{-- デフォルトのスプラッシュアート (num: 0) --}}
                <img src="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/{{ $champion->id }}_0.jpg" alt="{{ $champion->name }} スプラッシュアート" style="width: 100%; height: auto;">
            </div>
        @endif
    </div>
    
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
            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $spell['image']['full'] }}" alt="スキルアイコン" width="50">
            <p>{!! $spell['description'] !!}</p>
        @endforeach
        <hr>
    @endif

    <h3>スキン一覧</h3>
        <div class="skin-thumbnails">
            @foreach ($championApiData['skins'] as $skin)
                {{-- スキンのサムネイル画像 --}}
                <img src="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/{{ $champion->id }}_{{ $skin['num'] }}.jpg" alt="{{ $skin['name'] }}" style="width: 100px; cursor: pointer; border: 2px solid transparent;">
            @endforeach
        </div>
</body>
</html>