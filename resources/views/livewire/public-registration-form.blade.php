<div>
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Information -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ $pengaturan->judul ?? '' }}
            </h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">
                {{ $pengaturan->sub_judul ?? '' }}
            </h2>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-800 text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Batas Akhir Pendaftaran: {{ $pengaturan->tanggal_tutup ?? '' }}</strong>
                </p>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="form-section">
            <h2 class="form-title">
                <i class="fas fa-user-plus mr-2"></i>
                FORM PENDAFTARAN PESERTA PELATIHAN
            </h2>
            <div class="form-content">
                <form wire:submit="create" class="space-y-6">

                    <!-- Syarat dan Ketentuan -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Syarat dan Ketentuan:
                        </h3>
                        <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                            @if (!empty($pengaturan->persyaratan))
                                @foreach ($pengaturan->persyaratan as $item)
                                    <li>
                                        {{ $item['nama'] ?? $item }}
                                    </li>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500">Tidak ada persyaratan tersedia.</p>
                            @endif
                        </ul>
                    </div>

                    {{ $this->form }}

                    <!-- Submit Button -->
                    <div class="text-center py-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center justify-center mx-auto">
                            <i class="fas fa-paper-plane mr-2"></i>
                            KIRIM PENDAFTARAN
                        </button>
                        <p class="text-sm text-red-600 mt-2">
                            Dengan mengirim formulir ini, saya menyetujui semua syarat dan ketentuan yang berlaku
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('success'))
            <div class="alert-success">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert-error">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- CEK NIK -->
        <div class="form-section" x-data="cekNik()">
            <h2 class="form-title bg-red-800 text-white px-4 py-2 rounded-t-lg flex items-center">
                <i class="fa fa-search mr-2"></i>
                CEK NIK ANDA!
            </h2>

            <div class="form-content p-4 border border-red-300 rounded-b-lg">

                <!-- Input & Tombol -->
                <div class="flex items-center space-x-2">
                    <input type="text" maxlength="16" class="border rounded-lg p-3 flex-1"
                        placeholder="Masukkan NIK Anda" x-model="nik">

                    <button @click="check()"
                        class="bg-red-700 hover:bg-red-800 text-white py-2 px-4 rounded-lg inline-flex items-center transition"
                        :disabled="loading">
                        <template x-if="!loading">
                            <span class="flex items-center space-x-2">
                                <i class="fa fa-search"></i>
                                <span>CEK NIK</span>
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center space-x-2">
                                <i class="fa fa-spinner fa-spin"></i>
                                <span>Memeriksa...</span>
                            </span>
                        </template>
                    </button>
                </div>


                <!-- HASIL -->
                <div class="mt-4 space-y-4">

                    <!-- NIK ditemukan -->
                    <template x-if="result">
                        <div class="p-4 bg-green-100 border border-green-300 rounded-lg space-y-2">
                            <h3 class="font-semibold text-green-800 mb-2 flex items-center">
                                <i class="fa fa-check-circle mr-2"></i>
                                NIK Ditemukan!
                            </h3>

                            <p><strong>Nama:</strong> <span x-text="result.nama"></span></p>
                            <p><strong>NIK:</strong> <span x-text="result.nik"></span></p>
                            <p><strong>Alamat:</strong> <span x-text="result.alamat"></span></p>
                            <p><strong>Poktan:</strong> <span x-text="result.poktan"></span></p>

                            <!-- Daftar Kegiatan -->
                            <template x-if="result.kegiatan && result.kegiatan.length > 0">
                                <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="font-semibold text-blue-900 mb-1 flex items-center">
                                        <i class="fa fa-list mr-2"></i> Kegiatan yang Pernah Diikuti
                                    </h4>
                                    <ul class="list-disc list-inside text-blue-900">
                                        <template x-for="kg in result.kegiatan" :key="kg.kode">
                                            <li>
                                                <strong x-text="kg.nama"></strong>
                                                (<span x-text="kg.kode"></span>) —
                                                <span x-text="kg.mulai"></span> s/d <span x-text="kg.selesai"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="result.kegiatan && result.kegiatan.length === 0">
                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-yellow-800 font-semibold">
                                        Peserta belum mengikuti kegiatan apa pun.
                                    </p>
                                </div>
                            </template>

                            <!-- FORM DAFTAR PELATIHAN -->
                            <div class="mt-4 p-4 bg-white border border-gray-300 rounded-lg shadow-sm">
                                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <i class="fa fa-edit mr-2 text-blue-600"></i>
                                    Daftar Pelatihan Baru
                                </h3>

                                <div class="space-y-3">

                                    <!-- Pilih Kegiatan -->
                                    <div>
                                        <label class="font-medium text-gray-700">Pilih Jenis Pelatihan</label>
                                        <select x-model="kegiatan_id" class="border rounded-lg p-2 w-full mt-1">
                                            <option value="">-- Pilih Pelatihan --</option>

                                            @foreach (\App\Models\Kegiatan::aktif()->get() as $kg)
                                                <option value="{{ $kg->id }}">
                                                    {{ $kg->kode_pelatihan }} —
                                                    {{ $kg->nama_pelatihan }}
                                                    ({{ $kg->tanggal_mulai->format('d M') }} -
                                                    {{ $kg->tanggal_selesai->format('d M Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tombol Daftar -->
                                    <button @click="daftarPelatihan()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg inline-flex items-center justify-center transition">
                                        <span class="flex items-center space-x-2">
                                            <i class="fa fa-paper-plane"></i>
                                            <span>Daftar Pelatihan</span>
                                        </span>
                                    </button>

                                    <!-- Alert sukses -->
                                    <template x-if="notif">
                                        <div class="mt-2 p-3 rounded bg-green-100 border border-green-300 text-green-800"
                                            x-text="notif">
                                        </div>
                                    </template>

                                    <!-- Alert gagal -->
                                    <template x-if="notif_error">
                                        <div class="mt-2 p-3 rounded bg-red-100 border border-red-300 text-red-800"
                                            x-text="notif_error">
                                        </div>
                                    </template>

                                </div>
                            </div>

                        </div>
                    </template>

                    <!-- NIK tidak ditemukan -->
                    <template x-if="error">
                        <div class="p-4 bg-red-100 border border-red-300 rounded-lg">
                            <h3 class="font-semibold text-red-800 flex items-center">
                                <i class="fa fa-times-circle mr-2"></i>
                                <span x-text="error"></span>
                            </h3>
                        </div>
                    </template>

                </div>
            </div>
        </div>

        <!-- Informasi Fasilitas -->
        <div class="form-section">
            <h2 class="form-title">
                <i class="fas fa-gift mr-2"></i>
                FASILITAS PESERTA
            </h2>
            <div class="form-content">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <ul class="text-sm text-green-800 list-disc list-inside space-y-1">
                        @if (!empty($pengaturan->fasilitas))
                            @foreach ($pengaturan->fasilitas as $item)
                                <li>
                                    {{ $item['nama'] ?? $item }}
                                </li>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Tidak ada fasilitas tersedia.</p>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Informasi Lokasi -->
        <div class="form-section">
            <h2 class="form-title">
                <i class="fas fa-map-marker-alt mr-2"></i>
                LOKASI PELATIHAN
            </h2>
            <div class="form-content">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        <strong>{{ $pengaturan->info ?? '' }}</strong>
                    </p>
                    <p class="text-gray-600 flex items-start">
                        <i class="fas fa-map-pin mr-3 text-red-500 mt-1"></i>
                        {{ $pengaturan->lokasi ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function cekNik() {
            return {
                nik: '',
                loading: false,
                result: null,
                error: null,
                kegiatan_id: '',
                notif: null,
                notif_error: null,

                async check() {
                    this.loading = true;
                    this.result = null;
                    this.error = null;

                    try {
                        const response = await fetch(`/api/cek-nik?nik=${this.nik}`);
                        const data = await response.json();

                        if (data.success) {
                            this.result = data.data;
                        } else {
                            this.error = data.message;
                        }
                    } catch (e) {
                        this.error = 'Terjadi kesalahan saat memeriksa NIK.';
                        console.error(e);
                    }

                    this.loading = false;
                },

                async daftarPelatihan() {
                    this.notif = null;
                    this.notif_error = null;

                    if (!this.kegiatan_id) {
                        this.notif_error = "Silakan pilih pelatihan terlebih dahulu.";
                        return;
                    }

                    try {
                        const send = await fetch('/api/daftar-pelatihan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                nik: this.nik,
                                kegiatan_id: this.kegiatan_id,
                            })
                        });

                        const data = await send.json();

                        if (data.success) {
                            this.notif = data.message;
                        } else {
                            this.notif_error = data.message;
                        }
                    } catch (e) {
                        this.notif_error = "Terjadi kesalahan saat mendaftar pelatihan.";
                        console.error(e);
                    }
                }
            }
        }
    </script>
</div>
