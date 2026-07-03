<x-filament::page>
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kegiatan yang Diikuti</h2>
            <p class="text-sm text-gray-500">Konfirmasi kebersediaan untuk mengikuti kegiatan</p>
        </div>
        <div class="p-4">
            @php $diikuti = $this->getKegiatanDiikuti(); @endphp

            @if(count($diikuti) > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Nama Pelatihan</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Jadwal</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Status</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($diikuti as $item)
                                <tr>
                                    <td class="px-3 py-2 font-medium whitespace-nowrap">{{ $item['kode'] }}</td>
                                    <td class="px-3 py-2">{{ $item['nama'] }}</td>
                                    <td class="px-3 py-2 text-center text-xs text-gray-500 whitespace-nowrap">
                                        {{ $item['mulai'] }} - {{ $item['selesai'] }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        @if($item['status'] === 'bersedia')
                                            <x-filament::badge color="info">Bersedia</x-filament::badge>
                                        @else
                                            <x-filament::badge color="success">Diterima</x-filament::badge>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center whitespace-nowrap">
                                        @if($item['status'] === 'bersedia')
                                            <x-filament::badge color="info">Sudah Dikonfirmasi</x-filament::badge>
                                        @else
                                            <x-filament::button
                                                size="xs"
                                                wire:click="bersediaKegiatan('{{ $item['id'] }}')"
                                                wire:confirm="Yakin Anda bersedia mengikuti kegiatan ini?"
                                                color="success"
                                                icon="heroicon-o-check"
                                            >
                                                Bersedia
                                            </x-filament::button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center text-gray-500">
                    <x-heroicon-o-calendar class="mx-auto mb-2 h-10 w-10 text-gray-300" />
                    <p class="font-medium">Tidak ada kegiatan diterima</p>
                    <p class="text-sm">Belum ada kegiatan yang diterima saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament::page>
