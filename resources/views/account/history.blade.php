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

        @if (empty($processedMatches))
            <p>最近の対戦履歴がありません。</p>
        @else
            @foreach ($processedMatches as $match)
                <div class="match-card {{ $match['win'] ? 'win' : 'loss' }}">
                    
                    {{-- 左側：ゲームモードと結果 --}}
                    <div class="match-info">
                        <p class="match-game-mode">{{ $match['gameMode'] }}</p>
                        <p class="match-result {{ $match['win'] ? 'win' : 'loss' }}">
                            {{ $match['win'] ? '勝利' : '敗北' }}
                        </p>
                        <p class="match-duration">
                            {{ round($match['gameDuration'] / 60) }} 分
                        </p>
                    </div>

                    {{-- 中央：プレイヤーの詳細情報 --}}
                    <div class="match-player-details">
                        <div class="details-top">
                             {{-- チャンピオンアイコン --}}
                            <div class="match-champion-icon">
                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $match['championName'] }}.png" alt="{{ $match['championName'] }}">
                            </div>
                            {{-- KDA --}}
                            <div class="match-kda-section">
                                <p class="match-kda">
                                    {{ $match['kills'] }} / <span class="deaths">{{ $match['deaths'] }}</span> / {{ $match['assists'] }}
                                </p>
                            </div>
                        </div>
                        <div class="details-bottom">
                             {{-- 使用アイテム --}}
                             <div class="match-items">
                                @foreach ($match['items'] as $itemId)
                                    <div class="match-item-icon">
                                        @if($itemId !== 0)
                                            <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/item/{{ $itemId }}.png" alt="item-icon">
                                        @else
                                            <div class="empty-item-slot"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 右側：対面プレイヤー --}}
                    @if ($match['opponentChampionName'])
                        <div class="match-opponent">
                            <span>vs</span>
                            <div class="match-champion-icon opponent-icon">
                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $match['opponentChampionName'] }}.png" alt="{{ $match['opponentChampionName'] }}">
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        @endif
    </div>
</body>
</html>

