@php
    // Data notifikasi dummy dengan inisial nama (tanpa gambar)
    $notifications = [
        [
            'id' => '1',
            'name' => 'Terry Franci',
            'initials' => 'TF',
            'timeKey' => 'minAgo',
            'timeCount' => 5,
            'status' => 'online',
            'message' => 'Permintaan akses Anda telah disetujui'
        ],
        [
            'id' => '2',
            'name' => 'Alena Franci',
            'initials' => 'AF',
            'timeKey' => 'minAgo',
            'timeCount' => 8,
            'status' => 'online',
            'message' => 'Mengirim pesan baru kepada Anda'
        ],
        [
            'id' => '3',
            'name' => 'Jocelyn Kenter',
            'initials' => 'JK',
            'timeKey' => 'minAgo',
            'timeCount' => 15,
            'status' => 'online',
            'message' => 'Menambahkan tugas baru'
        ],
        [
            'id' => '4',
            'name' => 'Brandon Philips',
            'initials' => 'BP',
            'timeKey' => 'hrAgo',
            'timeCount' => 1,
            'status' => 'offline',
            'message' => 'Membagikan dokumen dengan Anda'
        ],
        // Duplikasi untuk testing scroll
        [
            'id' => '5',
            'name' => 'Terry Franci',
            'initials' => 'TF',
            'timeKey' => 'minAgo',
            'timeCount' => 5,
            'status' => 'online',
            'message' => 'Permintaan akses Anda telah disetujui'
        ],
        [
            'id' => '6',
            'name' => 'Alena Franci',
            'initials' => 'AF',
            'timeKey' => 'minAgo',
            'timeCount' => 8,
            'status' => 'online',
            'message' => 'Mengirim pesan baru kepada Anda'
        ],
        [
            'id' => '7',
            'name' => 'Jocelyn Kenter',
            'initials' => 'JK',
            'timeKey' => 'minAgo',
            'timeCount' => 15,
            'status' => 'online',
            'message' => 'Menambahkan tugas baru'
        ],
        [
            'id' => '8',
            'name' => 'Brandon Philips',
            'initials' => 'BP',
            'timeKey' => 'hrAgo',
            'timeCount' => 1,
            'status' => 'offline',
            'message' => 'Membagikan dokumen dengan Anda'
        ],
    ];
@endphp

<x-ui.dropdown-menu>
    <x-ui.dropdown-menu.trigger>
        <span class="relative">
            <x-svg.bell class="size-6" />
            <span class="absolute -top-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"></span>
        </span>
    </x-ui.dropdown-menu.trigger>

    <x-ui.dropdown-menu.content align="end" :side-offset="12" class="w-85 p-0">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <h5 class="text-sm font-medium text-gray-800 dark:text-white">{{ __('Notifikasi') }}</h5>
            <span class="rounded-md bg-brand-50 px-2 py-0.5 text-xs text-brand-500 dark:bg-brand-500/15">4 Baru</span>
        </div>

        <ul class="h-112 overflow-y-auto">
            @foreach($notifications as $notif)
            <li>
                <a href="#" class="flex items-start gap-4 border-b border-gray-100 px-5 py-4 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-50 text-sm font-semibold text-brand-500 dark:bg-brand-500/15">
                        {{ $notif['initials'] }}
                    </span>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800 dark:text-white/90">
                            <span class="font-semibold">{{ $notif['name'] }}</span>
                            {{ $notif['message'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __($notif['timeKey']) }} {{ $notif['timeCount'] }}
                        </p>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>

        <div class="border-t border-gray-200 px-5 py-3 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            <a href="#" class="text-brand-500 hover:underline">{{ __('Lihat Semua Notifikasi') }}</a>
        </div>
    </x-ui.dropdown-menu.content>
</x-ui.dropdown-menu>