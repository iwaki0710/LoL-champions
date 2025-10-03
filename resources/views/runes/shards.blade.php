<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サブパス - ステータスシャード</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="shards-container">
        <h1>サブパス (ステータスシャード)</h1>
        <p>メインパスとサブパスの組み合わせに応じて、3つのステータスボーナスを選択します。</p>
        
        {{-- 画像は public/img/shards/ 以下に配置してください --}}
        <div class="shard-row">
            <div class="shard-item"><img src="{{ asset('img/shards/adaptive-force.png') }}" alt="アダプティブフォース"><p>アダプティブフォース+9</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/attack-speed.png') }}" alt="攻撃速度"><p>攻撃速度+10%</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/ability-haste.png') }}" alt="スキルヘイスト"><p>スキルヘイスト+8</p></div>
        </div>
        <div class="shard-row">
            <div class="shard-item"><img src="{{ asset('img/shards/adaptive-force.png') }}" alt="アダプティブフォース"><p>アダプティブフォース+9</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/armor.png') }}" alt="物理防御"><p>物理防御+6</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/magic-resist.png') }}" alt="魔法防御"><p>魔法防御+8</p></div>
        </div>
        <div class="shard-row">
            <div class="shard-item"><img src="{{ asset('img/shards/health.png') }}" alt="体力"><p>体力+15-140</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/tenacity.png') }}" alt="行動妨害耐性"><p>行動妨害耐性+10%</p></div>
            <div class="shard-item"><img src="{{ asset('img/shards/armor.png') }}" alt="物理防御"><p>物理防御+6</p></div>
        </div>
    </div>
</body>
</html>