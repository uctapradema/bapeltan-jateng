<x-filament::page>
    @if($kegiatan)
        <div style="display:grid !important;grid-template-columns:1fr 380px !important;gap:24px !important;align-items:start !important;">

            {{-- KIRI: Info Kegiatan + Timeline --}}
            <div style="min-width:0;">
                {{-- Header --}}
                <div class="mb-4">
                    <a href="{{ route('filament.peserta.pages.peserta-dashboard') }}"
                       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 mb-2">
                        <x-heroicon-m-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $kegiatan['nama'] }}</h1>
                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                        <span>Kode: <strong>{{ $kegiatan['kode'] }}</strong></span>
                        <span>{{ $kegiatan['mulai'] }} - {{ $kegiatan['selesai'] }}</span>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="relative">
                    <div class="absolute left-[15px] top-3 bottom-3 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                    @foreach($tahapans as $i => $t)
                        <div class="relative flex items-start gap-4 pb-6 last:pb-0">
                            {{-- Ikon --}}
                            <div class="relative z-10 flex-shrink-0 mt-0.5">
                                @if($t['status'] === 'completed')
                                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                        <x-heroicon-m-check class="w-5 h-5 text-white" />
                                    </div>
                                @elseif($t['status'] === 'active')
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center ring-4 ring-blue-100 dark:ring-blue-900">
                                        <x-heroicon-m-arrow-right class="w-5 h-5 text-white" />
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <x-heroicon-m-lock-closed class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                    </div>
                                @endif
                            </div>

                            {{-- Konten --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold {{ $t['status'] === 'locked' ? 'text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-white' }}">
                                                {{ $t['nama'] }}
                                            </span>
                                            @if($t['tipe'] === 'harian')
                                                <x-filament::badge size="xs" color="warning">Harian</x-filament::badge>
                                            @endif
                                        </div>
                                        @if($t['deskripsi'])
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $t['deskripsi'] }}</p>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0">
                                        @if($t['status'] === 'completed')
                                            <x-filament::badge size="sm" color="success">Selesai</x-filament::badge>
                                        @elseif($t['status'] === 'active')
                                            @if($t['link'])
                                                <a href="{{ $t['link'] }}" target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                                                    <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                                                    Buka
                                                </a>
                                            @else
                                                <x-filament::button
                                                    size="sm"
                                                    wire:click="selesaikanTahapan({{ $t['id'] }})"
                                                    color="primary"
                                                >
                                                    Selesaikan
                                                </x-filament::button>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">Terkunci</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KANAN: Sidebar Summary --}}
            <div style="position:sticky !important;top:80px !important;">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Ringkasan</h3>
                    </div>
                    <div class="px-4 py-3 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Progress</span>
                            <span class="text-sm font-bold {{ $persentase == 100 ? 'text-green-500' : 'text-blue-500' }}">{{ $persentase }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500
                                {{ $persentase == 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                style="width: {{ $persentase }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 text-center">{{ $completedTahapans }}/{{ $totalTahapans }} tahap selesai</div>

                        <hr class="border-gray-200 dark:border-gray-700" />

                        <div class="space-y-1">
                            @foreach($tahapans as $t)
                                <div class="flex items-center gap-2 py-1">
                                    @if($t['status'] === 'completed')
                                        <div class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></div>
                                        <span class="text-xs text-gray-500 line-through">{{ $t['nama'] }}</span>
                                    @elseif($t['status'] === 'active')
                                        <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                        <span class="text-xs font-medium text-blue-500">{{ $t['nama'] }}</span>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 flex-shrink-0"></div>
                                        <span class="text-xs text-gray-400">{{ $t['nama'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament::page>
