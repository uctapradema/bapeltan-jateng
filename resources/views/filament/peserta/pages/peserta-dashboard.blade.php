<x-filament::page>
    {{-- Stats Cards --}}
    <div style="display:grid !important;grid-template-columns:repeat(4,1fr) !important;gap:8px !important;margin-bottom:24px !important;">
        <div style="text-align:center;background:var(--gray-800,#1f2937);border-radius:8px;padding:12px 8px;">
            <div style="font-size:20px;font-weight:700;color:#3b82f6;">{{ $this->getJumlahPelatihan() }}</div>
            <div style="font-size:12px;color:#9ca3af;">Total</div>
        </div>
        <div style="text-align:center;background:var(--gray-800,#1f2937);border-radius:8px;padding:12px 8px;">
            <div style="font-size:20px;font-weight:700;color:#22c55e;">{{ $this->getPelatihanDiterima() }}</div>
            <div style="font-size:12px;color:#9ca3af;">Diterima</div>
        </div>
        <div style="text-align:center;background:var(--gray-800,#1f2937);border-radius:8px;padding:12px 8px;">
            <div style="font-size:20px;font-weight:700;color:#f59e0b;">{{ $this->getPelatihanPending() }}</div>
            <div style="font-size:12px;color:#9ca3af;">Pending</div>
        </div>
        <div style="text-align:center;background:var(--gray-800,#1f2937);border-radius:8px;padding:12px 8px;">
            <div style="font-size:20px;font-weight:700;color:#06b6d4;">{{ $this->getPelatihanSelesai() }}</div>
            <div style="font-size:12px;color:#9ca3af;">Selesai</div>
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
                                            wire:click="daftarKegiatan({{ $item['id'] }})"
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
