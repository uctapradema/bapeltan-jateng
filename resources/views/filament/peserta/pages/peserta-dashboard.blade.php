<x-filament::page>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-6">
        <div class="text-center bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
            <div class="text-xl font-bold text-blue-500">{{ $this->getJumlahPelatihan() }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="text-center bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
            <div class="text-xl font-bold text-green-500">{{ $this->getPelatihanDiterima() }}</div>
            <div class="text-xs text-gray-500">Diterima</div>
        </div>
        <div class="text-center bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
            <div class="text-xl font-bold text-yellow-500">{{ $this->getPelatihanPending() }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="text-center bg-white dark:bg-gray-900 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
            <div class="text-xl font-bold text-cyan-500">{{ $this->getPelatihanSelesai() }}</div>
            <div class="text-xs text-gray-500">Selesai</div>
        </div>
    </div>

    {{-- Kegiatan Tersedia --}}
    <x-filament::section>
        <x-slot name="heading">Kegiatan Tersedia</x-slot>

        @php $kegiatan = $this->getKegiatanTersedia(); @endphp

        @if(count($kegiatan) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Nama Pelatihan</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Jadwal</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Kuota</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($kegiatan as $item)
                            <tr>
                                <td class="px-3 py-2 font-medium whitespace-nowrap">{{ $item['kode'] }}</td>
                                <td class="px-3 py-2">{{ $item['nama'] }}</td>
                                <td class="px-3 py-2 text-center text-xs text-gray-500 whitespace-nowrap">
                                    {{ $item['mulai'] }} - {{ $item['selesai'] }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="font-medium">{{ $item['terdaftar'] }}</span>/<span class="text-gray-500">{{ $item['kuota'] }}</span>
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if($item['status_daftar'] === 'pending')
                                        <x-filament::badge color="warning">Pending</x-filament::badge>
                                    @elseif($item['status_daftar'] === 'diterima')
                                        <x-filament::badge color="success">Diterima</x-filament::badge>
                                    @elseif($item['status_daftar'] === 'selesai')
                                        <x-filament::badge color="info">Selesai</x-filament::badge>
                                    @elseif($item['status_daftar'] === 'ditolak')
                                        <x-filament::badge color="danger">Ditolak</x-filament::badge>
                                    @elseif(!$item['kuota_tersedia'])
                                        <x-filament::badge color="danger">Penuh</x-filament::badge>
                                    @else
                                        <x-filament::button
                                            size="xs"
                                            wire:click="daftarKegiatan('{{ $item['id'] }}')"
                                            wire:confirm="Yakin ingin mendaftar kegiatan ini?"
                                            color="primary"
                                        >
                                            Daftar
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
                <p class="font-medium">Tidak ada kegiatan</p>
                <p class="text-sm">Belum ada kegiatan tersedia saat ini.</p>
            </div>
        @endif
    </x-filament::section>
</x-filament::page>
