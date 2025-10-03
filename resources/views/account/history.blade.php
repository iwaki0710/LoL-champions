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
            <p class="error">{{ session('errors')->first('searchError') }}</p>
        @endif

        <div id="match-list">
            @if (empty($processedMatches))
                <p>最近の対戦履歴がありません。</p>
            @else
                @foreach ($processedMatches as $match)
                    <div class="match-card {{ $match['win'] ? 'win' : 'loss' }}">
                        <div class="match-info">
                            <div class="match-game-mode">{{ $match['gameMode'] }}</div>
                            <div class="match-result {{ $match['win'] ? 'win' : 'loss' }}">{{ $match['win'] ? '勝利' : '敗北' }}</div>
                            <div class="match-duration">{{ floor($match['gameDuration'] / 60) }}分 {{ $match['gameDuration'] % 60 }}秒</div>
                        </div>
                        
                        <div class="match-player-details">
                            <div class="details-top">
                                <div class="match-champion-details">
                                    <div class="match-champion-icon">
                                        <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $match['championName'] }}.png" alt="{{ $match['championName'] }}">
                                    </div>
                                    <div class="spell-rune-icons">
                                        {{-- サモナースペルの列 --}}
                                        <div class="icon-col">
                                            @if ($match['spell1'])
                                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $match['spell1']->image_full }}" alt="{{ $match['spell1']->name }}">
                                            @endif
                                            @if ($match['spell2'])
                                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/spell/{{ $match['spell2']->image_full }}" alt="{{ $match['spell2']->name }}">
                                            @endif
                                        </div>
                                        {{-- ルーンの列 --}}
                                        <div class="icon-col">
                                            @if ($match['keystoneRune'])
                                                <img class="rune-icon" src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($match['keystoneRune']->icon_path) }}" alt="{{ $match['keystoneRune']->name }}">
                                            @endif
                                            @if ($match['subRuneStyle'])
                                                <img class="rune-icon" src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/{{ strtolower($match['subRuneStyle']->icon_path) }}" alt="{{ $match['subRuneStyle']->name }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="kda-cs-details">
                                    <div class="kda-section">
                                        <p class="match-kda">{{ $match['kills'] }} / <span class="deaths">{{ $match['deaths'] }}</span> / {{ $match['assists'] }}</p>
                                        <p class="kda-ratio">{{ $match['kda'] }} KDA</p>
                                    </div>
                                    <div class="cs-details">
                                        <p>CS {{ $match['totalCS'] }}</p>
                                        <p class="cs-per-minute">({{ $match['csPerMinute'] }}/m)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="details-bottom">
                                <div class="match-items">
                                    @for ($i = 0; $i < 7; $i++)
                                        @if ($match['items'][$i] ?? null)
                                            <div class="match-item-icon">
                                                <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/item/{{ $match['items'][$i] }}.png" alt="Item">
                                            </div>
                                        @else
                                            <div class="empty-item-slot"></div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        
                        @if ($match['opponentChampionName'])
                            <div class="match-opponent">
                                <span>対面</span>
                                <div class="opponent-icon">
                                    <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $match['opponentChampionName'] }}.png" alt="{{ $match['opponentChampionName'] }}">
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        @if (count($processedMatches) === 10)
            <div class="show-more-container">
                <button id="show-more-btn" class="show-more-button" data-puuid="{{ $puuid }}">さらに10件表示</button>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/history.js') }}"></script>

</body>
</html>