<x-filament::page>
    @php
        $kegiatanList = $this->getKegiatanVideoList();
        $selected = $selectedKegiatanId ? $this->getSelectedKegiatan() : null;
        $videoCount = count($kegiatanList);
    @endphp

    <div
        x-data="{ open: @js($isPlaying && $selected), loading: false }"
        x-on:keydown.escape.window="if(open) { open = false; $wire.stopPlay(); }"
        x-on:open-modal.window="open = true"
        x-on:close-modal.window="open = false; $wire.stopPlay();"
    >

        {{-- Header --}}
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-gray-500">Klik video untuk memutar</p>
            @if($videoCount > 0)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-100 dark:bg-primary-900/30 px-3 py-1 text-xs font-medium text-primary-700 dark:text-primary-300">
                    <x-heroicon-o-play-circle class="h-3.5 w-3.5" />
                    {{ $videoCount }} Video{{ $videoCount !== 1 ? 's' : '' }}
                </span>
            @endif
        </div>

        {{-- Video Grid --}}
        @if($videoCount > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($kegiatanList as $item)
                    @php
                        $isActive = $selectedKegiatanId === $item['kegiatan_id'];
                        $statusColorClass = match($item['status_reg']) {
                            'selesai' => 'bg-green-500/90',
                            'bersedia' => 'bg-blue-500/90',
                            default => 'bg-yellow-500/90',
                        };
                        $statusLabel = match($item['status_reg']) {
                            'selesai' => 'Selesai',
                            'bersedia' => 'Aktif',
                            default => 'Diterima',
                        };
                    @endphp
                    <div
                        wire:click="playVideo('{{ $item['kegiatan_id'] }}')"
                        x-on:click="$dispatch('open-modal')"
                        class="group relative rounded-xl border overflow-hidden cursor-pointer transition-all duration-200 bg-white dark:bg-gray-900
                            {{ $isActive
                                ? 'border-primary-500 ring-2 ring-primary-500/30 shadow-md'
                                : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-600 hover:shadow-lg hover:-translate-y-0.5'
                            }}"
                    >
                        {{-- Thumbnail --}}
                        <div class="relative aspect-video bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            @if($item['thumbnail_url'])
                                <img
                                    src="{{ $item['thumbnail_url'] }}"
                                    alt="{{ $item['nama'] }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <x-heroicon-o-video-camera class="h-8 w-8 text-gray-300" />
                                </div>
                            @endif

                            {{-- Play overlay --}}
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-red-600/90 group-hover:bg-red-600 shadow-lg flex items-center justify-center transition-all duration-200 group-hover:scale-110">
                                    <svg class="w-6 h-6 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Status badge --}}
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center rounded-full {{ $statusColorClass }} px-2 py-0.5 text-[10px] font-semibold text-white backdrop-blur-sm">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-3">
                            <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug">{{ $item['nama'] }}</h4>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $item['kode'] }} &middot; {{ $item['jenis'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- No videos --}}
            <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 py-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                        <x-heroicon-o-play-circle class="h-8 w-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Belum Ada Video</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm">Anda belum terdaftar di pelatihan yang memiliki video, atau video belum tersedia.</p>
                </div>
            </div>
        @endif

        {{-- Player Modal --}}
        @if($selected)
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                style="display: none;"
            >
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" x-on:click="open = false; $wire.stopPlay();"></div>

                {{-- Modal Content --}}
                <div
                    class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                >
                    {{-- Header --}}
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="min-w-0 flex-1 mr-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate text-sm sm:text-base">{{ $selected['nama'] }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $selected['kode'] }} &middot; {{ $selected['jenis'] }}</p>
                        </div>
                        <button
                            x-on:click="open = false; $wire.stopPlay();"
                            class="flex-shrink-0 p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        >
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>
                    </div>

                    {{-- Video --}}
                    <div class="p-4 sm:p-6">
                        @if(!$isPlaying && $selected['thumbnail_url'])
                            <div
                                wire:click="playVideo"
                                x-on:click="loading = true"
                                class="relative w-full cursor-pointer group rounded-lg overflow-hidden shadow-lg"
                                style="padding-top: 56.25%;"
                            >
                                <div x-show="loading" class="absolute inset-0 bg-gray-200 dark:bg-gray-700 animate-pulse rounded-lg">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 animate-pulse"></div>
                                    </div>
                                </div>
                                <img
                                    src="{{ $selected['thumbnail_url'] }}"
                                    alt="{{ $selected['nama'] }}"
                                    class="absolute inset-0 w-full h-full object-cover"
                                    x-show="!loading"
                                    x-on:load="loading = false"
                                    loading="lazy"
                                />
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors flex items-center justify-center" x-show="!loading">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-red-600 group-hover:bg-red-700 transition-all duration-200 group-hover:scale-110 shadow-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4" x-show="!loading">
                                    <p class="text-white font-medium text-sm sm:text-base">Klik untuk memutar</p>
                                    <p class="text-white/70 text-xs mt-0.5">Tekan ESC atau X untuk menutup</p>
                                </div>
                            </div>
                        @elseif($isPlaying && $selected['embed_url'])
                            <div class="relative w-full rounded-lg overflow-hidden shadow-lg" style="padding-top: 56.25%;">
                                <iframe
                                    class="absolute inset-0 w-full h-full"
                                    src="{{ $selected['embed_url'] }}?autoplay=1"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <div class="flex items-center justify-center py-12 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                <div class="text-center">
                                    <x-heroicon-o-video-camera class="mx-auto mb-2 h-12 w-12 text-gray-300" />
                                    <p class="font-medium text-gray-500">Video tidak tersedia</p>
                                    <p class="text-sm text-gray-400 mt-1">Link video tidak valid.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
