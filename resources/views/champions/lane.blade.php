<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>LoL {{ $laneFilter ?? '全' }}レーン チャンピオン一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1 class="lol">{{ $laneFilter ?? '' }}レーン チャンピオン一覧</h1>

    <div class="explanation-box">
        <div class="explanation">
            @php
                $laneDescriptions = [
                    'Top' => 'トップレーンはマップの最上部に位置し、基本的にタイマンで戦います。タンクやファイターといった耐久力の高いチャンピオンが主に担当し、チームの最前線で敵の攻撃を受け止めます。',
                    'Jungle' => 'ジャングルはレーンの間に広がる森で、ミニオンではなく中立モンスターを狩ります。各レーンに奇襲を仕掛ける（ガンク）ことで、味方を助け、有利な状況を作り出す重要な役割を担います。',
                    'Middle' => 'ミッドレーンはマップの中央に位置し、通常はメイジやアサシンといった攻撃力の高いチャンピオンが担当します。マップ全体に素早く移動できるため、他のレーンを支援する能力が求められます。',
                    'Bottom' => 'ボットレーンはマップの最下部に位置し、通常はADC（マークスマン）とサポートの2人組で戦います。ADCは遠距離からの継続火力が得意で、サポートはADCを援護します。',
                    'Support' => 'サポートはボットレーンでADCを助ける役割です。敵の妨害や味方の回復、視界の確保など、直接的な戦闘以外の面でチームを支えます。'
                ];
                $description = $laneDescriptions[$laneFilter] ?? 'LoLのゲーム性を理解しましょう。';
            @endphp
            <h2 class="explanation-h2">{{ $laneFilter ?? '' }}レーンの特徴</h2>
            <p>{!! nl2br(e($description)) !!}</p>
        </div>
    </div>
    
<hr>

<x-lane-icons />

<x-filter-form :difficultyFilter="$difficultyFilter" :roleFilter="$roleFilter" />

<hr>

<x-champions-list :filteredChampions="$filteredChampions" :version="$version" />
</body>
</html>