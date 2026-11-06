<div>
    @if(session('sukses'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">{{ session('sukses') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">{{ session('error') }}</div>
    @endif

    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Paket Soal</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola kumpulan soal untuk dijadikan paket ujian.</p>
        </div>
        <a href="{{ route('paket-soal.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Paket Baru
        </a>
    </div>

    <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama paket..." class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        <select wire:model.live="mapel_id" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <option value="">Semua Mata Pelajaran</option>
            @foreach($mapels as $mapel) <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option> @endforeach
        </select>
        <select wire:model.live="status" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="aktif">Aktif</option>
            <option value="arsip">Arsip</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Nama Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Total Soal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Status</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 bg-white dark:divide-gray-800 dark:bg-gray-900">
                    @forelse($pakets as $p)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $p->nama_paket }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $p->mataPelajaran->nama_mapel }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $p->total_soal }} Soal</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $p->durasi_menit }} Menit</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                    {{ $p->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($p->status === 'arsip' ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('paket-soal.edit', $p->id) }}" class="mr-3 text-brand-600 hover:text-brand-700 dark:text-brand-400">Edit</a>
                                @if($p->status !== 'arsip')
                                    <button wire:click="arsip({{ $p->id }})" wire:confirm="Arsipkan paket ini?" class="mr-3 text-gray-600 hover:text-gray-700 dark:text-gray-400">Arsip</button>
                                @endif
                                <button wire:click="hapusPermanen({{ $p->id }})" wire:confirm="HAPUS PERMANEN? Hanya bisa jika belum digunakan di jadwal ujian." class="text-red-600 hover:text-red-700 dark:text-red-400">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-700 dark:text-gray-400">Tidak ada data paket soal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $pakets->links() }}</div>
</div>