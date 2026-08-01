<x-filament::page>
    @if(count($this->sertifikats) > 0)
        @php $count = count($this->sertifikats); @endphp
        <div class="{{ $count > 4 ? 'max-h-[600px] overflow-y-auto pr-2' : '' }}">
            <div class="grid gap-3 sm:grid-cols-2 {{ $count > 4 ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }}">
                @foreach($this->sertifikats as $s)
                    <div class="fi-card fi-shadow rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                            <div class="flex items-center gap-2.5">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                                    <x-heroicon-o-document-check class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $s['nama'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s['kode'] }} &middot; {{ $s['jenis'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Selesai</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $s['tanggal_selesai'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400">No. Sertifikat</span>
                                <span class="font-mono text-xs text-gray-700 dark:text-gray-300">{{ $s['nomor'] }}</span>
                            </div>
                        </div>
                        <div class="p-3 border-t border-gray-200 dark:border-gray-700 flex gap-1.5">
                            @if($s['has_sertifikat'])
                                <a href="{{ $s['download_url'] }}" target="_blank"
                                   class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg bg-primary-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 transition">
                                    <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />
                                    Download
                                </a>
                                <a href="{{ $s['preview_url'] }}" target="_blank"
                                   class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg bg-white dark:bg-gray-800 px-2.5 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <x-heroicon-o-eye class="w-3.5 h-3.5" />
                                    Preview
                                </a>
                            @else
                                <div class="flex-1 text-center text-xs text-gray-400 dark:text-gray-500 py-1.5">
                                    Sertifikat belum tersedia
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if($count > 4)
            <p class="mt-2 text-center text-xs text-gray-400 dark:text-gray-500">Scroll untuk melihat {{ $count }} sertifikat</p>
        @endif
    @else
        <div class="py-16 text-center text-gray-500">
            <x-heroicon-o-document-check class="mx-auto mb-3 h-10 w-10 text-gray-300 dark:text-gray-600" />
            <p class="font-medium">Belum ada sertifikat</p>
            <p class="text-sm mt-1">Sertifikat akan muncul setelah Anda menyelesaikan pelatihan.</p>
        </div>
    @endif
</x-filament::page>
