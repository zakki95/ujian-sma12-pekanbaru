@props(['href', 'icon', 'active' => false])
<li>
    <a href="{{ $href }}" class="menu-item group"
        :class="[
            $active ? 'menu-item-active' : 'menu-item-inactive',
            (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'
        ]">
        <span :class="$active ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
            <x-dynamic-component :component="'svg.' . $icon" />
        </span>
        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" class="menu-item-text flex items-center gap-2">
            {{ $slot }}
        </span>
    </a>
</li>