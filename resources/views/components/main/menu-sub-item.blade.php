@props(['href', 'active' => false])
<li>
    <a href="{{ $href }}" class="menu-dropdown-item"
        :class="$active ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
        {{ $slot }}
    </a>
</li>