<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $item->name }} - アイテム詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="item-detail-container">
        <div class="item-image-section">
            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/item/{{ $item->id }}.png" alt="{{ $item->name }}の画像">
            <h1>{{ $item->name }}</h1>
            <p class="item-gold">{{ $item->total_gold }} ゴールド</p>
        </div>
        <div class="item-info-section">
            <h2>アイテムの効果</h2>
            <div class="item-description">
                {!! $item->description !!}
            </div>

            <hr>

            <h2>どんなチャンピオンが買う？</h2>
            <div class="item-advice">
                @if (in_array('Damage', $item->tags) && in_array('CriticalStrike', $item->tags))
                    <p>通常攻撃でダメージを出すマークスマン（ADC）や、一部のファイター（ヤスオ、ヨネなど）に最適です。</p>
                @elseif (in_array('SpellDamage', $item->tags))
                    <p>魔力でダメージを出すメイジや、魔法ダメージ系のアサシンが主に購入します。</p>
                @elseif (in_array('Armor', $item->tags) || in_array('Health', $item->tags))
                    <p>敵の物理攻撃から身を守る必要があるタンクや、前線で戦うファイターが購入します。</p>
                @elseif (in_array('MagicResist', $item->tags))
                    <p>敵の魔法攻撃から身を守る必要があるタンクや、メイジと戦うチャンピオンが購入します。</p>
                @elseif (in_array('Boots', $item->tags))
                    <p>全てのチャンピオンが移動速度を上げるために購入します。</p>
                @else
                    <p>このアイテムは、特定の状況やチャンピオンに合わせて様々な役割で使われます。</p>
                @endif
            </div>

            @if ($fromItems->isNotEmpty())
                <h2>素材アイテム</h2>
                <div class="item-list">
                    @foreach ($fromItems as $fromItem)
                        {{-- 各素材アイテムを、そのアイテムの詳細ページへリンク --}}
                        <a href="{{ route('items.show', ['item' => $fromItem->id]) }}" class="item-link">
                            <div class="item-card">
                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/item/{{ $fromItem->id }}.png" alt="{{ $fromItem->name }}の画像">
                                <p class="item-name">{{ $fromItem->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</body>
</html>