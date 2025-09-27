<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント検索</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <div class="search-page-container">
        <h1>アカウント検索</h1>
        
        <div class="search-container">
            {{-- エラーメッセージ表示 --}}
            @error('searchError')
                <p class="error">{{ $message }}</p>
            @enderror
            {{-- 成功メッセージ表示 --}}
            @if (session('success'))
                <p class="success">{{ session('success') }}</p>
            @endif

            <form action="{{ route('account.show') }}" method="POST">
                @csrf
                <input type="text" name="gameName" placeholder="アカウント名 (例: Riot)" required>
                <span>#</span>
                <input type="text" name="tagLine" placeholder="タグライン (例: JP1)" required>
                <button type="submit">検索</button>
            </form>
        </div>

        <hr>
        <div class="history-container">
            <h2>検索履歴</h2>
            @if ($histories->isNotEmpty())
                <ul class="history-list">
                    @foreach ($histories as $history)
                        <li>
                            <a href="{{ route('account.history', ['puuid' => $history->puuid]) }}" class="history-link">
                                <span class="history-name">{{ $history->gameName }}</span>
                                <span class="history-tag">#{{ $history->tagLine }}</span>
                            </a>
                            <form action="{{ route('account.history.destroy', $history->id) }}" method="POST" onsubmit="return confirm('この履歴を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">削除</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>検索履歴はありません。</p>
            @endif
        </div>
    </div>
</body>
</html>