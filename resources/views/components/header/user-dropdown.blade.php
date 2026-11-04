@php
    use Illuminate\Support\Facades\Storage;

    $user = auth()->user();
    $userName = $user->nama_lengkap ?? 'Pengguna';
    $userEmail = $user->email ?? '';

    $initials = strtoupper(collect(explode(' ', $userName))
        ->map(fn ($n) => mb_substr($n, 0, 1))
        ->take(2)
        ->implode(''));

    $fotoUrl = null;
    if (!empty($user->foto)) {
        if (str_starts_with($user->foto, 'http://') || str_starts_with($user->foto, 'https://')) {
            $fotoUrl = $user->foto;
        } else {
            $fotoUrl = Storage::url($user->foto);
        }
    }
@endphp

<x-ui.dropdown-menu>
    <x-ui.dropdown-menu.trigger class="dropdown-toggle flex items-center text-gray-700 dark:text-gray-400">
        <span class="me-3 h-11 w-11 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
            @if ($fotoUrl)
                <img width="44" height="44" src="{{ $fotoUrl }}" alt="{{ $userName }}" class="object-cover w-full h-full" />
            @else
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">{{ $initials }}</span>
            @endif
        </span>

        <span class="me-1 block text-sm font-medium">{{ $userName }}</span>

        <x-svg.chevron-down class="size-5 text-gray-500 duration-200 dark:text-gray-400" x-bind:class="open ? 'rotate-180' : 'rotate-0'" />
    </x-ui.dropdown-menu.trigger>

    <x-ui.dropdown-menu.content align="end" :side-offset="12" class="w-65 p-3">
        <div class="px-3 py-2">
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ $userName }}</span>
            @if ($userEmail)
                <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">{{ $userEmail }}</span>
            @endif
        </div>

        <x-ui.dropdown-menu.separator />

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
        <x-ui.dropdown-menu.item variant="destructive" x-on:click.capture="document.getElementById('logout-form').submit()">
            <x-svg.log-out class="size-6" />
            {{ __('userDropdown.signOut') }}
        </x-ui.dropdown-menu.item>
    </x-ui.dropdown-menu.content>
</x-ui.dropdown-menu>