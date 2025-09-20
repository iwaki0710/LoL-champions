<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>LoL チャンピオン一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1 class="lol">LoLを知ろう</h1>
    <div class="explanation-box">
        <div class="explanation">
            <h2 class="explanation-h2">LoLの基本的なルール</h2>
            <p>LoLは、5人のプレイヤーからなる2つのチームに分かれて対戦するゲームです。<br>マップには3本の道（レーン）があり、この道を仲間と一緒に進み、<br>敵チームの陣地にあるネクサスの破壊を目指します。</p>
            <h2 class="explanation-h2">ゲームの流れ</h2>
            <p>敵チームのプレイヤーや、自動で動くミニオンを倒してお金を稼ぎ、アイテムを購入してチャンピオンを強化します。敵のタワーを壊しながら進み、最終的にネクサスを破壊できたら勝利となります。</p>
        </div>
        <div class="character-introduction">
            <h2 class="explanation-h2">魅力的なチャンピオン</h2>
            <p>LoLにはたくさんの魅力的なチャンピオンがいます。あなたは対戦ごとに好きなチャンピオンを1人選び、操作します。</p>
            <p>チャンピオンにはそれぞれ、得意なことや役割が異なります。</p>
            <ul>
                <li>敵を攻撃するのが得意なチャンピオン</li>
                <li>仲間を助けるのが得意なチャンピオン</li>
                <li>敵の攻撃に耐えるのが得意なチャンピオン</li>
                <li>...など</li>
            </ul>
            <p>このサイトでチャンピオンの特徴や難易度から自分に合ったチャンピオンを見つけよう！</p>
        </div>
    </div>

    </div>
        <hr>
    <h1 class="lol">LoL チャンピオン一覧</h1>

    <form action="{{ url('/champions') }}" method="GET" id="filter-form">
    <label for="difficulty">難易度で選別:</label>
    <select name="difficulty" id="difficulty" onchange="this.form.submit()">
        <option value="">すべて</option>
        <option value="star1" {{ $difficultyFilter === 'star1' ? 'selected' : '' }}>★</option>
        <option value="star2" {{ $difficultyFilter === 'star2' ? 'selected' : '' }}>★★</option>
        <option value="star3" {{ $difficultyFilter === 'star3' ? 'selected' : '' }}>★★★</option>
    </select>

    <label for="role">ロールで選別:</label>
    <select name="role" id="role" onchange="this.form.submit()">
        <option value="">すべて</option>
        @php
            $roles = ['Fighter', 'Tank', 'Mage', 'Assassin', 'Marksman', 'Support'];
        @endphp
        @foreach ($roles as $role)
            <option value="{{ $role }}" {{ $roleFilter === $role ? 'selected' : '' }}>{{ $role }}</option>
        @endforeach
    </select>
    
    <label for="lane">レーンで選別:</label>
    <select name="lane" id="lane" onchange="this.form.submit()">
        <option value="">すべて</option>
        @php
            $lanes = ['Top', 'Jungle', 'Middle', 'Bottom', 'Support'];
        @endphp
        @foreach ($lanes as $lane)
            <option value="{{ $lane }}" {{ $laneFilter === $lane ? 'selected' : '' }}>{{ $lane }}</option>
        @endforeach
    </select>
</form>
    
    <hr>
    @if (count($filteredChampions) > 0)
    <div class="champion-list">
        @foreach ($filteredChampions as $champion)
            <a href="{{ url('/champions/' . $champion->id) }}" class="champion-link">
                <div class="champion-card">
                    <h2>{{ $champion->name }}</h2>
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
                    <p>ロール: {{ implode(', ', $champion->tags) }}</p>
                    {{-- 画像はAPIから動的に取得します --}}
                    <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $champion->id }}.png" alt="{{ $champion->name }}の画像" width="100">
                </div>
            </a>
        @endforeach
        </div>
    @else
        <p>チャンピオン情報がありません。</p>
    @endif

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>