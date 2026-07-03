<x-filament::page>
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pelatihan</h2>
            <p class="text-sm text-gray-500">Klik untuk melihat tahapan pelatihan</p>
        </div>
        <div class="p-4">
            @php $kegiatanList = $this->getKegiatanList(); @endphp

            @if(count($kegiatanList) > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Nama Pelatihan</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Jadwal</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Progress</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($kegiatanList as $item)
                                <tr>
                                    <td class="px-3 py-2 font-medium whitespace-nowrap">{{ $item['kode'] }}</td>
                                    <td class="px-3 py-2">{{ $item['nama'] }}</td>
                                    <td class="px-3 py-2 text-center text-xs text-gray-500 whitespace-nowrap">
                                        {{ $item['mulai'] }} - {{ $item['selesai'] }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary-500 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500">{{ $item['completed'] }}/{{ $item['total'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center whitespace-nowrap">
                                        <a href="{{ \App\Filament\Peserta\Pages\KegiatanTahapanPage::getUrl(['kegiatanId' => $item['kegiatan_id']]) }}">
                                            <x-filament::button
                                                size="xs"
                                                color="primary"
                                                icon="heroicon-o-arrow-right"
                                            >
                                                Lihat Tahapan
                                            </x-filament::button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center text-gray-500">
                    <x-heroicon-o-academic-cap class="mx-auto mb-2 h-10 w-10 text-gray-300" />
                    <p class="font-medium">Tidak ada pelatihan aktif</p>
                    <p class="text-sm">Belum ada pelatihan yang sedang diikuti.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament::page>
