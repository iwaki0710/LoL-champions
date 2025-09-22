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
            {{-- 難易度（データベースから取得） --}}
            <p class="champion-difficulty">難易度: 
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
                    <span class="star">★</span>
                @endfor
            </p>
            <p>おすすめレーン</p>
                @if ($champion->lane)
                    <div class="recommended-lane-icon">
                        @php
                            $laneIconName = ($champion->lane === 'Bottom') ? 'bottom' : strtolower($champion->lane);
                        @endphp
                        <img src="{{ asset('img/lanes/' . $laneIconName . '.png') }}" alt="{{ $champion->lane }}レーン" class="lane-icon-small">
                    </div>
                @else
                    <p>推奨レーン情報がありません。</p>
                @endif
        </div>
        {{-- スプラッシュアートとスキンの表示 --}}
        @if (isset($championApiData['skins']))
            <div class="splash-container">
                {{-- デフォルトのスプラッシュアート (num: 0) --}}
                <img src="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/{{ $champion->id }}_0.jpg" alt="{{ $champion->name }} スプラッシュアート" style="width: 100%; height: auto;">
            </div>
        @endif
    </div>
    <div class="skills-container">
    {{-- パッシブ情報 --}}
    @if (isset($champion->passive))
        <div class="skill-item">
            <h3>パッシブ</h3>
            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/passive/{{ $champion->passive['image']['full'] }}" alt="パッシブアイコン" class="skill-icon">
            <h4 class="skill-name">{{ $champion->passive['name'] }}</h4>
            <p class="skill-description">{!! $champion->passive['description'] !!}</p>
        </div>
    @endif

    {{-- スキル情報 --}}
    @if (isset($champion->spells))
        @php
            $keys = ['Q', 'W', 'E', 'R'];
        @endphp
        @foreach ($champion->spells as $index => $spell)
            <div class="skill-item">
                <h3>{{ $keys[$index] }}</h3>
                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $spell['image']['full'] }}" alt="スキルアイコン" class="skill-icon">
                <h4 class="skill-name">{{ $spell['name'] }}</h4>
                <p class="skill-description">{!! $spell['description'] !!}</p>
            </div>
        @endforeach
    @endif
    </div>
<hr>

    <h3>スキン一覧</h3>
        <div class="skin-thumbnails">
            @foreach ($championApiData['skins'] as $skin)
                <img src="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/{{ $champion->id }}_{{ $skin['num'] }}.jpg" alt="{{ $skin['name'] }}">
            @endforeach
        </div>
        <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <div class="modal-content-container">
            <a class="prev" id="prevBtn">&#10094;</a>
            <a class="next" id="nextBtn">&#10095;</a>
            <img class="modal-content" id="modalImage">
        </div>
        <div id="caption"></div>
</div>
</div>
</div>
<script src="{{ asset('js/show.js') }}"></script>
</body>
</html>