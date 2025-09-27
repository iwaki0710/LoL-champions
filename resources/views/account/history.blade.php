<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>対戦履歴</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="match-history-container">
        <h1>対戦履歴</h1>

        @if (session('errors'))
            <p class="error">{{ session('errors')->first('historyError') }}</p>
        @endif

        @if (empty($matches))
            <p>最近の対戦履歴がありません。</p>
        @else
            {{-- 
                チャンピオン画像を表示するには、AccountControllerのhistoryメソッドで
                APIバージョン($version)を取得し、このビューに渡す必要があります。
                例: $version = Http::get("...versions.json")->json()[0];
                    return view('account.history', compact(..., 'version'));
            --}}
            @foreach ($matches as $match)
                {{-- 自分のプレイヤー情報を試合データから検索 --}}
                @php
                    $playerData = null;
                    foreach ($match['info']['participants'] as $participant) {
                        if ($participant['puuid'] === $puuid) {
                            $playerData = $participant;
                            break;
                        }
                    }
                @endphp

                {{-- プレイヤー情報が見つかった場合のみ表示 --}}
                @if ($playerData)
                    <div class="match-card {{ $playerData['win'] ? 'win' : 'loss' }}">
                        
                        {{-- チャンピオンアイコン --}}
                        <div class="match-champion-icon">
                            @isset($version)
                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $playerData['championName'] }}.png" alt="{{ $playerData['championName'] }}">
                            @endisset
                        </div>

                        {{-- 試合結果とKDA --}}
                        <div class="match-stats">
                            <p class="match-result {{ $playerData['win'] ? 'win' : 'loss' }}">
                                {{ $playerData['win'] ? '勝利' : '敗北' }}
                            </p>
                            <p class="match-kda">
                                {{ $playerData['kills'] }} / <span class="deaths">{{ $playerData['deaths'] }}</span> / {{ $playerData['assists'] }}
                            </p>
                            <p class="match-meta">
                                試合時間: {{ round($match['info']['gameDuration'] / 60) }} 分
                            </p>
                        </div>

                    </div>
                @endif
            @endforeach
        @endif
    </div>
</body>
</html>
