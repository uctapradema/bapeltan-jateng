<x-filament::page>
    {{-- Pengaturan Info --}}
    @if($pengaturan = $this->getPengaturan())
        <div class="mb-6 rounded-xl bg-primary-500/10 p-4 dark:bg-primary-500/5">
            <h2 class="text-lg font-bold text-primary-600 dark:text-primary-400">
                {{ $pengaturan->judul }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $pengaturan->sub_judul }}
            </p>
            @if($pengaturan->info)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                    {{ $pengaturan->info }}
                </p>
            @endif
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-2xl font-bold text-primary-600">{{ $this->getJumlahPelatihan() }}</div>
            <div class="text-sm text-gray-500">Total Pelatihan</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-2xl font-bold text-success-600">{{ $this->getPelatihanDiterima() }}</div>
            <div class="text-sm text-gray-500">Diterima</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-2xl font-bold text-warning-600">{{ $this->getPelatihanPending() }}</div>
            <div class="text-sm text-gray-500">Pending</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-800">
            <div class="text-2xl font-bold text-info-600">{{ $this->getPelatihanSelesai() }}</div>
            <div class="text-sm text-gray-500">Selesai</div>
        </div>
    </div>

    {{-- Daftar Pelatihan --}}
    <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold">Pelatihan Terbaru</h3>

        @php $daftar = $this->getDaftarPelatihan(); @endphp

        @if(count($daftar) > 0)
            <div class="space-y-3">
                @foreach($daftar as $item)
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <div>
                            <div class="font-medium">{{ $item['nama'] }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $item['kode'] }} | {{ $item['mulai'] }} - {{ $item['selesai'] }}
                            </div>
                        </div>
                        <x-filament::badge
                            :color="match($item['status']) {
                                'pending' => 'warning',
                                'diterima' => 'success',
                                'ditolak' => 'danger',
                                'selesai' => 'info',
                                default => 'gray',
                            }"
                        >
                            {{ ucfirst($item['status']) }}
                        </x-filament::badge>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-gray-500">
                <x-heroicon-o-academic-cap class="mx-auto mb-2 h-12 w-12 text-gray-300" />
                <p>Belum ada pelatihan yang didaftarkan.</p>
                <p class="text-sm">Silakan mendaftar pelatihan melalui menu Registrasi.</p>
            </div>
        @endif
    </div>
</x-filament::page>
