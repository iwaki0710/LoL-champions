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
                <li>仲間を助けたり、敵の動きを止めたりするのが得意なチャンピオン</li>
            </ul>
            <p>まずはあなたの好みに合ったチャンピオンを見つけてみましょう！</p>
        </div>
    </div>
    <hr>
<x-lane-icons />

<x-filter-form :difficultyFilter="$difficultyFilter" :roleFilter="$roleFilter" />

<hr>

<x-champions-list :filteredChampions="$filteredChampions" :version="$version" />

</body>
</html>