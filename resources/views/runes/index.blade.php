<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ルーン選択</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />
    <h1 style="text-align: center;">ルーンを選択</h1>

    <div class="rune-paths-container">
        @foreach ($paths as $path)
            <a href="{{ route('runes.show', ['pathId' => $path->id]) }}" class="rune-path-card">
                {{-- 正しくは$path->icon_path を使います --}}
                <img src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($path->icon_path) }}" alt="{{ $path->name }}">
                <p>{{ $path->name }}</p>
            </a>
        @endforeach

        <a href="{{ route('runes.show', ['pathId' => 'StatMods']) }}" class="rune-path-card">
            <img src="{{ asset('img/runes/stat-mods.png') }}" alt="シャード">
            <p>シャード</p>
        </a>
    </div>

</body>
</html>