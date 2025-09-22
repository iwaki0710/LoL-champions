@props(['difficultyFilter', 'roleFilter'])

<div class="filtering-container">
    <form action="{{ request()->url() }}" method="GET" class="filter-form">
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
