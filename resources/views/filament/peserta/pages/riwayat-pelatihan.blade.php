<x-filament::page>
    @if(empty($riwayat))
        <div class="text-center py-12">
            <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-400" />
            <p class="mt-4 text-gray-500">Belum ada riwayat pelatihan.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($riwayat as $item)
                <x-filament::section>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $item['kegiatan']->nama_pelatihan }}
                            </h3>
                            <div class="mt-1 flex flex-wrap gap-2 text-sm text-gray-500">
                                <span>Kode: {{ $item['kegiatan']->kode_pelatihan }}</span>
                                <span>•</span>
                                <span>{{ $item['tipe_kegiatan'] }}</span>
                                <span>•</span>
                                <span>{{ $item['kegiatan']->tanggal_mulai?->format('d M Y') ?? '-' }} - {{ $item['kegiatan']->tanggal_selesai?->format('d M Y') ?? '-' }}</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                Terdaftar: {{ $item['tanggal_daftar']->format('d M Y') }}
                                @if($item['tanggal_selesai'])
                                    • Selesai: {{ $item['tanggal_selesai']->format('d M Y') }}
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @if($item['status'] === 'selesai')
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">Tahapan</div>
                                    <div class="text-sm font-medium">{{ $item['completed_tahapans'] }}/{{ $item['total_tahapans'] }}</div>
                                </div>
                                <div class="w-24">
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-center mt-1 text-gray-500">{{ $item['persentase'] }}%</div>
                                </div>
                                <x-filament::badge color="success">Selesai</x-filament::badge>
                                <a href="{{ route('sertifikat.download', $item['id']) }}" target="_blank" class="text-primary-600 hover:text-primary-500">
                                    <x-heroicon-o-document-arrow-down class="w-5 h-5" />
                                </a>
                            @elseif($item['status'] === 'diterima' || $item['status'] === 'bersedia')
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">Tahapan</div>
                                    <div class="text-sm font-medium">{{ $item['completed_tahapans'] }}/{{ $item['total_tahapans'] }}</div>
                                </div>
                                <div class="w-24">
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-500 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-center mt-1 text-gray-500">{{ $item['persentase'] }}%</div>
                                </div>
                                <x-filament::badge color="primary">Berlangsung</x-filament::badge>
                            @elseif($item['status'] === 'pending')
                                <x-filament::badge color="warning">Menunggu</x-filament::badge>
                            @elseif($item['status'] === 'ditolak')
                                <x-filament::badge color="danger">Ditolak</x-filament::badge>
                            @endif
                        </div>
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    @endif
</x-filament::page>
