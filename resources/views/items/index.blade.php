<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>LoL アイテム一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    {{-- ヘッダーコンポーネントを読み込み --}}
    <x-header />

    <h1>アイテム一覧</h1>

    {{-- メインのフィルターナビゲーション --}}
    <div class="filter-nav">
        <a href="{{ route('items') }}" class="{{ !$filter ? 'active' : '' }}">全て</a>
        <a href="{{ route('items', ['filter' => 'category']) }}" class="{{ $filter === 'category' ? 'active' : '' }}">カテゴリ別</a>
        <a href="{{ route('items', ['filter' => 'completed']) }}" class="{{ $filter === 'completed' ? 'active' : '' }}">完成アイテム</a>
        <a href="{{ route('items', ['filter' => 'deleted']) }}" class="{{ $filter === 'deleted' ? 'active' : '' }}">使用出来ないアイテム</a>
    </div>

    {{-- 「カテゴリ別」ページでのみ表示するサブフィルター --}}
    @if ($filter === 'category')
        <div class="sub-filter-nav">
            @php
                $currentSubFilter = $sub_filter ?? '';
            @endphp
            <a href="{{ route('items', ['filter' => 'category']) }}" class="{{ !$currentSubFilter ? 'active' : '' }}">すべて</a>
            <a href="{{ route('items', ['filter' => 'category', 'sub_filter' => 'Jungle']) }}" class="{{ $currentSubFilter === 'Jungle' ? 'active' : '' }}">初期アイテム</a>
            <a href="{{ route('items', ['filter' => 'category', 'sub_filter' => 'Consumable']) }}" class="{{ $currentSubFilter === 'Consumable' ? 'active' : '' }}">消費アイテム</a>
            <a href="{{ route('items', ['filter' => 'category', 'sub_filter' => 'Vision']) }}" class="{{ $currentSubFilter === 'Vision' ? 'active' : '' }}">視界</a>
            <a href="{{ route('items', ['filter' => 'category', 'sub_filter' => 'Boots']) }}" class="{{ $currentSubFilter === 'Boots' ? 'active' : '' }}">ブーツ</a>
        </div>
    @endif

    {{-- 「完成アイテム」ページでのみ表示するチェックボックスフィルター --}}
    @if ($filter === 'completed')
        <div class="completed-item-filters">
            <fieldset>
                <legend>攻撃系</legend>
                <label><input type="checkbox" name="item-filter" value="Damage"> 攻撃力</label>
                <label><input type="checkbox" name="item-filter" value="AttackSpeed"> 攻撃速度</label>
                <label><input type="checkbox" name="item-filter" value="CriticalStrike"> クリティカル</label>
                <label><input type="checkbox" name="item-filter" value="LifeSteal"> ライフスティール</label>
            </fieldset>
            <fieldset>
                <legend>魔力系</legend>
                <label><input type="checkbox" name="item-filter" value="SpellDamage"> 魔力</label>
                <label><input type="checkbox" name="item-filter" value="Mana"> マナ</label>
            </fieldset>
            <fieldset>
                <legend>防御系</legend>
                <label><input type="checkbox" name="item-filter" value="Health"> 体力</label>
                <label><input type="checkbox" name="item-filter" value="Armor"> 物理防御</label>
                <label><input type="checkbox" name="item-filter" value="MagicResist"> 魔法防御</label>
            </fieldset>
             <fieldset>
                <legend>その他</legend>
                <label><input type="checkbox" name="item-filter" value="AbilityHaste"> スキルヘイスト</label>
                <label><input type="checkbox" name="item-filter" value="NonbootsMovement"> 移動速度</label>
            </fieldset>
        </div>
    @endif

    <hr>

    {{-- アイテム一覧表示エリア --}}
    <div class="item-list">
        @foreach ($items as $item)
            {{-- 各アイテムを詳細ページへリンクさせる --}}
            <a href="{{ route('items.show', ['item' => $item->id]) }}" class="item-link filterable-item" data-tags="{{ json_encode($item->tags) }}">
                <div class="item-card">
                    <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/item/{{ $item->id }}.png" alt="{{ $item->name }}の画像">
                    <p class="item-name">{{ $item->name }}</p>
                    {{-- ゴールド表示部分 --}}
                    <div class="item-gold">
                        <span>{{ $item->total_gold }}</span>
                        {{-- public/img/icons/gold.png を表示 --}}
                        <img src="{{ asset('img/icons/gold.png') }}" alt="ゴールド" class="gold-icon">
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if ($filter === 'completed')
        <script src="{{ asset('js/item-filter.js') }}"></script>
    @endif
</body>
</html>

