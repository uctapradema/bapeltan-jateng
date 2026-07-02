<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-filament::section>
            <x-slot name="heading">
                Export Data Peserta
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Download data seluruh peserta dalam format CSV. Data mencakup NIK, nama, alamat, kontak, dan informasi lainnya.
                </p>

                <x-filament::button
                    wire:click="exportPeserta"
                    icon="heroicon-o-arrow-down-tray"
                >
                    Download CSV Peserta
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Export Data Registrasi
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Download data seluruh registrasi pelatihan dalam format CSV. Data mencakup NIK peserta, nama, kegiatan, status, dan tanggal daftar.
                </p>

                <x-filament::button
                    wire:click="exportRegistrasi"
                    icon="heroicon-o-arrow-down-tray"
                >
                    Download CSV Registrasi
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>
</x-filament::page>
