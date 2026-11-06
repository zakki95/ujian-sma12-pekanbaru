<div x-data="{ 
    jenisSoal: @entangle('jenis_soal'),
    showLightbox: false,
    lightboxSrc: '',
    openLightbox(src) {
        this.lightboxSrc = src;
        this.showLightbox = true;
    },
    closeLightbox() {
        this.showLightbox = false;
        this.lightboxSrc = '';
    }
}">
    @if(session('sukses'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('sukses') }}
        </div>
    @endif
    @if($errors->has('save'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            {{ $errors->first('save') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('bank-soal.index') }}" class="inline-flex items-center text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Bank Soal
        </a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $bankSoalId ? 'Edit Soal' : 'Tambah Soal Baru' }}</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lengkapi informasi di bawah ini untuk membuat atau memperbarui soal ujian.</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Metadata -->
        <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Informasi Dasar</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select wire:model="mata_pelajaran_id" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Soal <span class="text-red-500">*</span></label>
                    <select wire:model.live="jenis_soal" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="pg">Pilihan Ganda</option>
                        <option value="pg_kompleks">Pilihan Ganda Kompleks</option>
                        <option value="benar_salah">Benar/Salah</option>
                        <option value="esai">Esai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tingkat Kesukaran <span class="text-red-500">*</span></label>
                    <select wire:model="tingkat_kesukaran" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="mudah">Mudah</option>
                        <option value="sedang">Sedang</option>
                        <option value="sulit">Sulit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bobot Nilai <span class="text-red-500">*</span></label>
                    <input wire:model="bobot_nilai" type="number" step="0.01" min="0.01" max="100" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    @error('bobot_nilai') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                    <select wire:model="status" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="draft">Draft</option>
                        <option value="aktif">Aktif</option>
                        <option value="arsip">Arsip</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Konten Soal -->
        <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Konten Soal</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teks Soal <span class="text-red-500">*</span></label>
                    <textarea wire:model="teks_soal" rows="5" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Tuliskan pertanyaan di sini..."></textarea>
                    @error('teks_soal') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Soal (Opsional, Max 2MB)</label>
                    <input wire:model="gambar_soal" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-400">
                    @if($existing_gambar_soal)
                        <div class="mt-3">
                            <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Klik gambar untuk melihat ukuran penuh:</p>
                            <img 
                                src="{{ Storage::url($existing_gambar_soal) . '?t=' . filemtime(storage_path('app/public/' . $existing_gambar_soal)) }}" 
                                @click="openLightbox('{{ Storage::url($existing_gambar_soal) }}')"
                                class="block cursor-zoom-in rounded-lg border border-gray-300 bg-gray-50 object-contain transition-transform hover:scale-105 hover:border-brand-400 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-brand-500" style="max-width: 200px; max-height: 96px; width: auto; height: auto;"
                                alt="Preview gambar soal"
                            >
                        </div>
                    @endif
                    @error('gambar_soal') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Pilihan Jawaban (Dinamis) -->
        <div x-show="jenisSoal !== 'esai'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Pilihan Jawaban</h3>
            @error('pilihan') <p class="mb-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">{{ $message }}</p> @enderror

            @foreach($pilihan as $index => $p)
                <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4 transition-colors hover:border-brand-300 dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-brand-700">
                    <div class="flex items-start gap-4">
                        <div class="mt-1.5">
                            @if($jenis_soal === 'pg' || $jenis_soal === 'benar_salah')
                                <input type="radio" wire:model="pilihan.{{ $index }}.is_benar" value="1" class="h-4 w-4 border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                            @else
                                <input type="checkbox" wire:model="pilihan.{{ $index }}.is_benar" value="1" class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                            @endif
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="flex gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-brand-100 text-sm font-bold text-brand-700 dark:bg-brand-900/30 dark:text-brand-400">
                                    {{ $p['label'] }}
                                </span>
                                <input wire:model="pilihan.{{ $index }}.teks_jawaban" type="text" placeholder="Teks jawaban..." class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            </div>
                            <div>
                                <input wire:model="pilihan.{{ $index }}.new_gambar" type="file" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:text-xs dark:file:bg-gray-700 dark:file:text-gray-300">
                                @if(isset($p['existing_gambar']) && $p['existing_gambar'])
                                    <div class="mt-2">
                                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Preview:</p>
                                        <img 
                                            src="{{ Storage::url($p['existing_gambar']) . '?t=' . filemtime(storage_path('app/public/' . $p['existing_gambar'])) }}" 
                                            class="block h-16 w-16 max-h-16 max-w-16 rounded border border-gray-300 bg-gray-50 object-contain dark:border-gray-700 dark:bg-gray-800"
                                            alt="Preview pilihan {{ $p['label'] }}"
                                        >
                                    </div>
                                @endif
                                @error("pilihan.{{ $index }}.new_gambar") <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        @if($jenis_soal === 'pg_kompleks' && count($pilihan) > 2)
                            <button type="button" wire:click="removePilihan({{ $index }})" class="mt-1 text-red-500 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Hapus Pilihan">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($jenis_soal === 'pg_kompleks')
                <button type="button" wire:click="addPilihan" class="mt-2 inline-flex items-center text-sm font-medium text-brand-600 transition-colors hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pilihan
                </button>
            @endif
        </div>

        <!-- Rubrik Esai -->
        <div x-show="jenisSoal === 'esai'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Rubrik Penilaian & Pedoman</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rubrik Esai <span class="text-red-500">*</span></label>
                <textarea wire:model="rubrik_esai" rows="5" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition-colors focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Tuliskan pedoman penilaian atau kunci jawaban esai di sini..."></textarea>
                @error('rubrik_esai') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="sticky bottom-4 z-10 flex justify-end gap-3 rounded-xl border border-gray-200 bg-white/90 p-4 shadow-lg backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/90 sm:static sm:bg-transparent sm:p-0 sm:shadow-none sm:backdrop-blur-none">
            <a href="{{ route('bank-soal.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-900">
                Batal
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-gray-900">
                <svg wire:loading wire:target="save" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">{{ $bankSoalId ? 'Perbarui Soal' : 'Simpan Soal' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>

    <!-- Lightbox Overlay -->
    <div 
        x-show="showLightbox"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeLightbox()"
        @keydown.escape.window="closeLightbox()"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        style="display: none;"
    >
        <button 
            @click.stop="closeLightbox()"
            class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white transition-colors hover:bg-white/20"
            aria-label="Tutup"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img 
            :src="lightboxSrc"
            @click.stop
            class="max-h-[90vh] max-w-[90vw] rounded-lg bg-white object-contain shadow-2xl"
            alt="Gambar diperbesar"
        >
    </div>
</div>