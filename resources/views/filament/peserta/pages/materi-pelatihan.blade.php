<x-filament::page>
    {{-- Pilihan Kegiatan --}}
    @if(count($kegiatans) > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Pilih Kegiatan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($kegiatans as $kegiatan)
                    <button
                        wire:click="selectKegiatan('{{ $kegiatan['id'] }}')"
                        class="p-4 rounded-xl border-2 text-left transition-all {{ $selectedKegiatanId === $kegiatan['id'] ? 'border-primary-500 bg-primary-50 dark:bg-primary-950' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}"
                    >
                        <div class="font-medium">{{ $kegiatan['nama'] }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $kegiatan['kode'] }} • {{ $kegiatan['jenis'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $kegiatan['tanggal_mulai'] }} - {{ $kegiatan['tanggal_selesai'] }}</div>
                        <div class="mt-2">
                            @if($kegiatan['status_reg'] === 'selesai')
                                <span class="badge badge-sm badge-success">Selesai</span>
                            @elseif($kegiatan['status_reg'] === 'bersedia')
                                <span class="badge badge-sm badge-info">Bersedia</span>
                            @else
                                <span class="badge badge-sm badge-primary">Aktif</span>
                            @endif
                            <span class="badge badge-sm badge-outline ml-1">{{ count($kegiatan['materis']) }} materi</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Daftar Materi --}}
    @if($selectedKegiatanId)
        @php $kegiatan = $this->getSelectedKegiatan(); @endphp
        @if($kegiatan)
            <div class="mb-4">
                <h3 class="text-lg font-semibold">{{ $kegiatan['nama'] }}</h3>
                <p class="text-sm text-gray-500">{{ count($kegiatan['materis']) }} materi tersedia</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($kegiatan['materis'] as $materi)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                        {{-- Thumbnail --}}
                        @if($materi['tipe'] === 'video_url' && $materi['thumbnail_url'])
                            <div class="relative aspect-video bg-gray-100 dark:bg-gray-800">
                                <img
                                    src="{{ $materi['thumbnail_url'] }}"
                                    alt="{{ $materi['judul'] }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                                @if($materi['embed_url'])
                                    <button
                                        wire:click="playVideo('{{ $materi['embed_url'] }}', '{{ $materi['judul'] }}')"
                                        class="absolute inset-0 flex items-center justify-center bg-black/30 hover:bg-black/50 transition-colors"
                                    >
                                        <div class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        @elseif($materi['tipe'] === 'video_file')
                            <div class="relative aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-green-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        @elseif($materi['tipe'] === 'dokumen')
                            <div class="aspect-video bg-blue-50 dark:bg-blue-950 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-xs text-blue-600 mt-1">Dokumen</span>
                                </div>
                            </div>
                        @elseif($materi['tipe'] === 'gambar')
                            <div class="aspect-video bg-gray-100 dark:bg-gray-800">
                                @if($materi['file_url'])
                                    <img src="{{ $materi['file_url'] }}" alt="{{ $materi['judul'] }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        @else
                            <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="p-3">
                            <div class="font-medium text-sm line-clamp-2">{{ $materi['judul'] }}</div>
                            @if($materi['deskripsi'])
                                <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $materi['deskripsi'] }}</div>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="badge badge-xs
                                    {{ match($materi['tipe']) {
                                        'video_url' => 'badge-info',
                                        'video_file' => 'badge-success',
                                        'dokumen' => 'badge-warning',
                                        'gambar' => 'badge-primary',
                                        default => 'badge-ghost',
                                    } }}">
                                    {{ match($materi['tipe']) {
                                        'video_url' => 'Video',
                                        'video_file' => 'Video File',
                                        'dokumen' => 'Dokumen',
                                        'gambar' => 'Gambar',
                                        default => $materi['tipe'],
                                    } }}
                                </span>
                                @if($materi['file_url'] && $materi['tipe'] !== 'gambar')
                                    <a href="{{ $materi['file_url'] }}" target="_blank" class="btn btn-xs btn-outline">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download
                                    </a>
                                @endif
                                @if($materi['url'] && $materi['tipe'] === 'video_url')
                                    <a href="{{ $materi['url'] }}" target="_blank" class="btn btn-xs btn-outline">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Buka
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @elseif(count($kegiatans) === 0)
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-500">Belum Ada Materi</h3>
            <p class="text-sm text-gray-400 mt-1">Materi pelatihan akan muncul di sini setelah admin mengunggahnya.</p>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-500">Pilih Kegiatan</h3>
            <p class="text-sm text-gray-400 mt-1">Pilih kegiatan di atas untuk melihat materi pelatihan.</p>
        </div>
    @endif

    {{-- Modal Video Player --}}
    @if($isPlaying)
        <div
            x-data
            x-on:keydown.escape.window="$wire.stopPlay()"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="relative w-full max-w-4xl mx-4">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-medium">{{ $currentJudul }}</h3>
                    <button
                        wire:click="stopPlay"
                        class="text-white hover:text-gray-300 transition-colors"
                    >
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Video Player --}}
                <div class="aspect-video rounded-xl overflow-hidden bg-black">
                    <iframe
                        src="{{ $currentEmbedUrl }}?autoplay=1"
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>

                {{-- Close Button --}}
                <div class="text-center mt-4">
                    <button
                        wire:click="stopPlay"
                        class="px-6 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors"
                    >
                        Tutup (ESC)
                    </button>
                </div>
            </div>
        </div>
    @endif
</x-filament::page>
