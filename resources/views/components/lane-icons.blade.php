<div class="lane-icons">
    <a href="{{ route('champions.index') }}" class="lane-icon-link {{ request()->routeIs('champions.index') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/all.png') }}" alt="すべて" class="lane-icon">
    </a>
    <a href="{{ route('champions.top') }}" class="lane-icon-link {{ request()->routeIs('champions.top') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/top.png') }}" alt="トップ" class="lane-icon">
    </a>
    <a href="{{ route('champions.jungle') }}" class="lane-icon-link {{ request()->routeIs('champions.jungle') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/jungle.png') }}" alt="ジャングル" class="lane-icon">
    </a>
    <a href="{{ route('champions.middle') }}" class="lane-icon-link {{ request()->routeIs('champions.middle') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/middle.png') }}" alt="ミドル" class="lane-icon">
    </a>
    <a href="{{ route('champions.bottom') }}" class="lane-icon-link {{ request()->routeIs('champions.bottom') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/bottom.png') }}" alt="ボット" class="lane-icon">
    </a>
    <a href="{{ route('champions.support') }}" class="lane-icon-link {{ request()->routeIs('champions.support') ? 'active' : '' }}">
        <img src="{{ asset('img/lanes/support.png') }}" alt="サポート" class="lane-icon">
    </a>
</div>