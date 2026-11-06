<div>
    @if(session('sukses'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">{{ session('sukses') }}</div>
    @endif
    @if($errors->has('save'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">{{ $errors->first('save') }}</div>
    @endif

    <div class="mb-6">
        <a href="{{ route('paket-soal.index') }}" class="inline-flex items-center text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Daftar Paket Soal
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $paketSoalId ? 'Edit Paket Soal' : 'Buat Paket Soal Baru' }}</h1>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Kolom Kiri: Metadata -->
            <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Informasi Paket</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Paket <span class="text-red-500">*</span></label>
                        <input wire:model="nama_paket" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        @error('nama_paket') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select wire:model="mata_pelajaran_id" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapels as $mapel) <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option> @endforeach
                        </select>
                        @error('mata_pelajaran_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durasi Ujian (Menit) <span class="text-red-500">*</span></label>
                        <input wire:model="durasi_menit" type="number" min="1" max="300" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        @error('durasi_menit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea wire:model="deskripsi" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="arsip">Arsip</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Pemilihan Soal -->
            <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Pilih Soal (Total: {{ count($orderedSelected) }})
                </h3>
                
                @if(!$mata_pelajaran_id)
                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                        Silakan pilih Mata Pelajaran terlebih dahulu untuk menampilkan daftar soal.
                    </div>
                @else
                    <div class="mb-4">
                        <input wire:model.live.debounce.300ms="searchSoal" type="text" placeholder="Cari teks soal..." class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Soal Tersedia -->
                        <div class="max-h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/50">
                            <h4 class="mb-2 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Tersedia</h4>
                            @forelse($availableQuestions as $q)
                                <div class="mb-2 flex items-start justify-between rounded border border-gray-200 bg-white p-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex-1 pr-2">
                                        <p class="line-clamp-2 text-gray-700 dark:text-gray-300">{{ Str::limit($q->teks_soal, 60) }}</p>
                                        <span class="mt-1 inline-block rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-400">{{ strtoupper($q->jenis_soal) }}</span>
                                    </div>
                                    @if(!in_array($q->id, $selectedBankSoalIds))
                                        <button type="button" wire:click="addSoal({{ $q->id }})" class="shrink-0 rounded bg-brand-100 p-1.5 text-brand-600 hover:bg-brand-200 dark:bg-brand-900/30 dark:text-brand-400" title="Tambah ke paket">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    @else
                                        <span class="shrink-0 rounded bg-green-100 p-1.5 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-xs text-gray-500">Tidak ada soal tersedia.</p>
                            @endforelse
                        </div>

                        <!-- Soal Terpilih -->
                        <div class="max-h-96 overflow-y-auto rounded-lg border border-brand-200 bg-brand-50/30 p-3 dark:border-brand-800 dark:bg-brand-900/10">
                            <h4 class="mb-2 text-xs font-semibold uppercase text-brand-600 dark:text-brand-400">Terpilih (Urutan)</h4>
                            @forelse($orderedSelected as $index => $q)
                                <div class="mb-2 flex items-center gap-2 rounded border border-brand-200 bg-white p-2 text-sm dark:border-brand-800 dark:bg-gray-800">
                                    <div class="flex flex-col gap-1">
                                        <button type="button" wire:click="moveUp({{ $index }})" class="flex items-center justify-center rounded bg-gray-100 p-1.5 text-gray-600 transition-colors hover:bg-brand-100 hover:text-brand-600 disabled:cursor-not-allowed disabled:opacity-30 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-brand-900/30 dark:hover:text-brand-400" @if($index === 0) disabled @endif title="Pindah ke atas">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="moveDown({{ $index }})" class="flex items-center justify-center rounded bg-gray-100 p-1.5 text-gray-600 transition-colors hover:bg-brand-100 hover:text-brand-600 disabled:cursor-not-allowed disabled:opacity-30 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-brand-900/30 dark:hover:text-brand-400" @if($index === count($orderedSelected) - 1) disabled @endif title="Pindah ke bawah">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-1">
                                        <p class="line-clamp-2 text-gray-700 dark:text-gray-300"><span class="font-bold text-brand-600">#{{ $index + 1 }}</span> {{ Str::limit($q->teks_soal, 50) }}</p>
                                    </div>
                                    <button type="button" wire:click="removeSoal({{ $index }})" class="shrink-0 text-red-500 hover:text-red-700" title="Hapus dari paket">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-center text-xs text-gray-500">Belum ada soal yang dipilih.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit -->
        <div class="sticky bottom-4 z-10 flex justify-end gap-3 rounded-xl border border-gray-200 bg-white/90 p-4 shadow-lg backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/90 sm:static sm:bg-transparent sm:p-0 sm:shadow-none sm:backdrop-blur-none">
            <a href="{{ route('paket-soal.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">Batal</a>
            <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-gray-900">
                <svg wire:loading wire:target="save" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">{{ $paketSoalId ? 'Perbarui Paket' : 'Simpan Paket' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>