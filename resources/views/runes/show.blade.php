<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ $path->name }} - ルーン詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="rune-detail-container">
        <div class="path-header">
            {{-- 正しくは$path->icon_path を使います --}}
            <img src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($path->icon_path) }}" alt="{{ $path->name }}">
            <h1>{{ $path->name }}</h1>
        </div>

        {{-- キーストーンの表示 --}}
        <div class="rune-section">
            <h2>キーストーン</h2>
            <div class="keystones-list">
                @foreach ($keystones as $rune)
                    <div class="rune-item">
                        {{-- こちらもCommunity DragonのURLに変更します --}}
                        <img src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($rune->icon_path) }}" alt="{{ $rune->name }}">
                        <h3>{{ $rune->name }}</h3>
                        <div class="description">{!! $rune->long_desc !!}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 通常ルーンの表示 --}}
        @foreach ($otherRunes as $slot => $runesInSlot)
            <div class="rune-section">
                <div class="runes-row">
                    @foreach ($runesInSlot as $rune)
                        <div class="rune-item">
                            {{-- こちらもCommunity DragonのURLに変更します --}}
                            <img src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($rune->icon_path) }}" alt="{{ $rune->name }}">
                            <h3>{{ $rune->name }}</h3>
                            <div class="description">{!! $rune->long_desc !!}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</body>
</html>