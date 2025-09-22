@props(['filteredChampions', 'version'])
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