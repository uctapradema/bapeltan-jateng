<x-filament::page>
    @php
        $statistik = $this->getStatistik();
        $pesertaPerKab = $this->getPesertaPerKabupaten();
        $pesertaPerType = $this->getPesertaPerKegiatanType();
        $registrasiPerStatus = $this->getRegistrasiPerStatus();
        $kegiatanTerbaru = $this->getKegiatanTerbaru();
    @endphp

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-sm text-gray-500">Total Peserta</div>
                <div class="text-2xl font-bold text-primary-600">{{ $statistik['total_peserta'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-sm text-gray-500">Total Kegiatan</div>
                <div class="text-2xl font-bold text-primary-600">{{ $statistik['total_kegiatan'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-sm text-gray-500">Total Registrasi</div>
                <div class="text-2xl font-bold text-primary-600">{{ $statistik['total_registrasi'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="text-sm text-gray-500">Selesai</div>
                <div class="text-2xl font-bold text-green-600">{{ $statistik['registrasi_selesai'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-filament::section>
                <x-slot name="heading">Peserta per Kabupaten</x-slot>
                <div class="space-y-2">
                    @forelse($pesertaPerKab as $item)
                        <div class="flex items-center justify-between">
                            <span class="text-sm">{{ $item['nama'] }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-500 rounded-full" style="width: {{ $statistik['total_peserta'] > 0 ? ($item['total'] / $statistik['total_peserta'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 w-8 text-right">{{ $item['total'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada data</p>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Peserta per Jenis Kegiatan</x-slot>
                <div class="space-y-2">
                    @forelse($pesertaPerType as $item)
                        <div class="flex items-center justify-between">
                            <span class="text-sm">{{ $item->nama_type }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-500 rounded-full" style="width: {{ $statistik['total_registrasi'] > 0 ? ($item->total / $statistik['total_registrasi'] * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 w-8 text-right">{{ $item->total }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada data</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-filament::section>
                <x-slot name="heading">Status Registrasi</x-slot>
                <div class="space-y-2">
                    @foreach($registrasiPerStatus as $item)
                        <div class="flex items-center justify-between">
                            <x-filament::badge color="{{ match($item->status) { 'pending' => 'warning', 'diterima' => 'success', 'ditolak' => 'danger', 'bersedia' => 'info', 'selesai' => 'success', default => 'gray' } }}">
                                {{ ucfirst($item->status) }}
                            </x-filament::badge>
                            <span class="text-sm font-medium">{{ $item->total }}</span>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Kegiatan Terbaru</x-slot>
                <div class="space-y-2">
                    @forelse($kegiatanTerbaru as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <div class="font-medium">{{ $item['nama_pelatihan'] }}</div>
                                <div class="text-xs text-gray-500">{{ $item['kegiatanType']['nama_type'] ?? '-' }} • {{ \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M Y') }}</div>
                            </div>
                            <x-filament::badge color="{{ $item['status'] === 'active' ? 'success' : 'danger' }}">
                                {{ $item['status'] === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </x-filament::badge>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada kegiatan</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament::page>
