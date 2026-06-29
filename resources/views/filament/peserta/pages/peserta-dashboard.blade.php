<x-filament::page>
    @php $tahapanList = $this->getTahapanData(); @endphp

    <div style="display:grid !important;grid-template-columns:1fr 380px !important;gap:24px !important;align-items:start !important;">

        {{-- KIRI: Stats + Kegiatan Tersedia --}}
        <div style="min-width:0;">
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
        </div>

        {{-- KANAN: Sidebar Tahapan --}}
        <div style="position:sticky !important;top:80px !important;">
            @if(count($tahapanList) > 0)
                @foreach($tahapanList as $keg)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden mb-4">
                        {{-- Header Sidebar --}}
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">{{ $keg['nama'] }}</h3>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-xs text-gray-500">{{ $keg['completed'] }}/{{ $keg['total'] }} tahap</span>
                                <span class="text-xs font-bold {{ $keg['persentase'] == 100 ? 'text-green-500' : 'text-blue-500' }}">{{ $keg['persentase'] }}%</span>
                            </div>
                            {{-- Progress Bar --}}
                            <div class="w-full h-1.5 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden mt-2">
                                <div class="h-full rounded-full transition-all duration-500
                                    {{ $keg['persentase'] == 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                    style="width: {{ $keg['persentase'] }}%"></div>
                            </div>
                        </div>

                        {{-- Timeline --}}
                        <div class="px-4 py-3">
                            <div class="relative">
                                {{-- Garis vertikal --}}
                                <div class="absolute left-[11px] top-3 bottom-3 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                                @foreach($keg['tahapans'] as $i => $t)
                                    <div class="relative flex items-start gap-3 pb-4 last:pb-0">
                                        {{-- Ikon status --}}
                                        <div class="relative z-10 flex-shrink-0 mt-0.5">
                                            @if($t['status'] === 'completed')
                                                <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                                    <x-heroicon-m-check class="w-3.5 h-3.5 text-white" />
                                                </div>
                                            @elseif($t['status'] === 'active')
                                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center ring-4 ring-blue-100 dark:ring-blue-900">
                                                    <x-heroicon-m-arrow-right class="w-3.5 h-3.5 text-white" />
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <x-heroicon-m-lock-closed class="w-3 h-3 text-gray-500 dark:text-gray-400" />
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Konten --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="min-w-0">
                                                    <span class="text-sm font-medium leading-tight {{ $t['status'] === 'locked' ? 'text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-white' }}">
                                                        {{ $t['nama'] }}
                                                    </span>
                                                    @if($t['deskripsi'])
                                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 leading-snug">{{ $t['deskripsi'] }}</p>
                                                    @endif
                                                </div>

                                                <div class="flex-shrink-0">
                                                    @if($t['status'] === 'completed')
                                                        <x-filament::badge size="xs" color="success">Selesai</x-filament::badge>
                                                    @elseif($t['status'] === 'active')
                                                        @if($t['link'])
                                                            <a href="{{ $t['link'] }}" target="_blank"
                                                               class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 transition">
                                                                <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
                                                                Buka
                                                            </a>
                                                        @else
                                                            <x-filament::button
                                                                size="xs"
                                                                wire:click="selesaikanTahapan({{ $t['id'] }})"
                                                                color="primary"
                                                            >
                                                                Selesaikan
                                                            </x-filament::button>
                                                        @endif
                                                    @else
                                                        <span class="text-[11px] text-gray-400">Terkunci</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 text-center">
                    <x-heroicon-o-academic-cap class="mx-auto mb-2 h-10 w-10 text-gray-300" />
                    <p class="text-sm font-medium text-gray-500">Belum ada pelatihan aktif</p>
                    <p class="text-xs text-gray-400 mt-1">Daftar kegiatan untuk memulai pelatihan</p>
                </div>
            @endif
        </div>
    </div>
</x-filament::page>
