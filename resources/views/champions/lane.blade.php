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
    {{-- フィルタリングUI --}}
    <div class="filtering-container">
        <div class="filter-group">
                <label for="lane">レーンで選別:</label>
                <select name="lane" id="lane" onchange="redirectLanePage(this.value)">
                    <option value="">すべて</option>
                    @php
                        $lanes = ['Top', 'Jungle', 'Middle', 'Bottom', 'Support'];
                    @endphp
                    @foreach ($lanes as $lane)
                        <option value="{{ strtolower($lane) }}" {{ ($laneFilter === $lane) ? 'selected' : '' }}>{{ $lane }}</option>
                    @endforeach
                </select>
            </div>
        <form action="{{ route('champions.' . strtolower($laneFilter)) }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label for="difficulty">難易度で選別:</label>
                <select name="difficulty" id="difficulty" onchange="this.form.submit()">
                    <option value="">すべて</option>
                    <option value="star1" {{ $difficultyFilter === 'star1' ? 'selected' : '' }}>★</option>
                    <option value="star2" {{ $difficultyFilter === 'star2' ? 'selected' : '' }}>★★</option>
                    <option value="star3" {{ $difficultyFilter === 'star3' ? 'selected' : '' }}>★★★</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="role">ロールで選別:</label>
                <select name="role" id="role" onchange="this.form.submit()">
                    <option value="">すべて</option>
                    @php
                        $roles = ['Fighter', 'Tank', 'Mage', 'Assassin', 'Marksman', 'Support'];
                    @endphp
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $roleFilter === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    <hr>
    {{-- チャンピオンリストの部分は index.blade.php と共通化できます --}}
    @if (count($filteredChampions) > 0)
    <div class="champion-list">
        @foreach ($filteredChampions as $champion)
            <a href="{{ url('/champions/' . $champion->id) }}" class="champion-link">
                <div class="champion-card">
                    <h2>{{ $champion->name }}</h2>
                    <p class="champion-difficulty">難易度: 
                        @php
                            $difficulty = $champion->difficulty;
                            if ($difficulty >= 0 && $difficulty <= 3) {
                                $stars = 1;
                            } elseif ($difficulty >= 4 && $difficulty <= 7) {
                                $stars = 2;
                            } else {
                                $stars = 3;
                            }
                        @endphp
                        @for ($i = 0; $i < $stars; $i++)
                            <span class="star">★</span>
                        @endfor
                    </p>
                    <p>ロール: {{ implode(', ', $champion->tags) }}</p>
                    <img src="https://ddragon.leagueoflegends.com/cdn/{{ $version }}/img/champion/{{ $champion->id }}.png" alt="{{ $champion->name }}の画像" width="100">
                </div>
            </a>
        @endforeach
        </div>
    @else
        <p>チャンピオン情報がありません。</p>
    @endif
    <script>
    function redirectLanePage(lane) {
        if (lane) {
            window.location.href = `/champions/${lane}`;
        } else {
            window.location.href = `/champions`;
        }
    }
    </script>
</body>
</html>