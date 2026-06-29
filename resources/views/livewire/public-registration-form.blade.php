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
            <div class="alert alert-warning max-w-md mx-auto">
                <span class="text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Batas Akhir Pendaftaran: {{ $pengaturan->tanggal_tutup ?? '' }}</strong>
                </span>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-error mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Registration Form -->
        <div class="card bg-white shadow-lg border border-gray-200 mb-8">
            <div class="card-body">
                <h2 class="card-title text-blue-800 text-lg mb-4">
                    <i class="fas fa-user-plus"></i>
                    FORM PENDAFTARAN PESERTA PELATIHAN
                </h2>

                <form wire:submit="create" class="space-y-6">

                    <!-- Syarat dan Ketentuan -->
                    <div class="alert alert-info">
                        <h3 class="font-semibold mb-2 flex items-center">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Syarat dan Ketentuan:
                        </h3>
                        <ul class="text-sm list-disc list-inside space-y-1">
                            @if (!empty($pengaturan->persyaratan))
                                @foreach ($pengaturan->persyaratan as $item)
                                    <li>{{ $item['nama'] ?? $item }}</li>
                                @endforeach
                            @else
                                <p class="text-sm opacity-70">Tidak ada persyaratan tersedia.</p>
                            @endif
                        </ul>
                    </div>

                    {{-- Data Personal --}}
                    <div class="divider text-blue-800 font-bold">
                        <i class="fas fa-user mr-1"></i> DATA PERSONAL
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Kabupaten --}}
                        <div class="form-control">
                            <label class="label" for="kabupaten_id">
                                <span class="label-text font-semibold">Kabupaten <span class="text-red-500">*</span></span>
                            </label>
                            <select wire:model="kabupaten_id" id="kabupaten_id" class="select select-bordered w-full">
                                <option value="">-- Pilih Kabupaten --</option>
                                @foreach(\App\Models\Kabupaten::orderBy('nama')->get() as $kab)
                                    <option value="{{ $kab->id }}">{{ $kab->nama }}</option>
                                @endforeach
                            </select>
                            @error('kabupaten_id') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIK --}}
                        <div class="form-control">
                            <label class="label" for="nik">
                                <span class="label-text font-semibold">NIK (16 digit) <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="nik" type="text" id="nik" maxlength="16" placeholder="Masukkan 16 digit NIK"
                                class="input input-bordered w-full" />
                            @error('nik') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="form-control md:col-span-2">
                            <label class="label" for="nama">
                                <span class="label-text font-semibold">Nama Lengkap (Huruf Kapital) <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="nama" type="text" id="nama" placeholder="Contoh: BUDI SANTOSO"
                                class="input input-bordered w-full" />
                            @error('nama') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="form-control">
                            <label class="label" for="tempat_lahir">
                                <span class="label-text font-semibold">Tempat Lahir <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="tempat_lahir" type="text" id="tempat_lahir" placeholder="Contoh: SEMARANG"
                                class="input input-bordered w-full" />
                            @error('tempat_lahir') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="form-control">
                            <label class="label" for="tanggal_lahir">
                                <span class="label-text font-semibold">Tanggal Lahir <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="tanggal_lahir" type="date" id="tanggal_lahir"
                                class="input input-bordered w-full" />
                            @error('tanggal_lahir') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="form-control">
                            <label class="label" for="nomor_telepon">
                                <span class="label-text font-semibold">Nomor Telepon <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="nomor_telepon" type="tel" id="nomor_telepon" placeholder="08xxxxxxxxxx"
                                class="input input-bordered w-full" />
                            @error('nomor_telepon') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="form-control">
                            <label class="label" for="jenis_kelamin">
                                <span class="label-text font-semibold">Jenis Kelamin <span class="text-red-500">*</span></span>
                            </label>
                            <select wire:model="jenis_kelamin" id="jenis_kelamin" class="select select-bordered w-full">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Data Tambahan --}}
                    <div class="divider text-blue-800 font-bold">
                        <i class="fas fa-id-card mr-1"></i> DATA TAMBAHAN
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Agama --}}
                        <div class="form-control">
                            <label class="label" for="agama">
                                <span class="label-text font-semibold">Agama <span class="text-red-500">*</span></span>
                            </label>
                            <select wire:model="agama" id="agama" class="select select-bordered w-full">
                                <option value="">-- Pilih --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('agama') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Status Pernikahan --}}
                        <div class="form-control">
                            <label class="label" for="status_pernikahan">
                                <span class="label-text font-semibold">Status Pernikahan <span class="text-red-500">*</span></span>
                            </label>
                            <select wire:model="status_pernikahan" id="status_pernikahan" class="select select-bordered w-full">
                                <option value="">-- Pilih --</option>
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                            @error('status_pernikahan') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Pendidikan Terakhir --}}
                        <div class="form-control">
                            <label class="label" for="pendidikan_terakhir">
                                <span class="label-text font-semibold">Pendidikan Terakhir <span class="text-red-500">*</span></span>
                            </label>
                            <select wire:model="pendidikan_terakhir" id="pendidikan_terakhir" class="select select-bordered w-full">
                                <option value="">-- Pilih --</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA/SMK">SMA/SMK</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            @error('pendidikan_terakhir') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="form-control">
                            <label class="label" for="pekerjaan">
                                <span class="label-text font-semibold">Pekerjaan <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="pekerjaan" type="text" id="pekerjaan" placeholder="Contoh: Petani"
                                class="input input-bordered w-full" />
                            @error('pekerjaan') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Usaha Tani --}}
                        <div class="form-control">
                            <label class="label" for="usaha_tani">
                                <span class="label-text font-semibold">Usaha Tani <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="usaha_tani" type="text" id="usaha_tani" placeholder="Contoh: Padi, Jagung"
                                class="input input-bordered w-full" />
                            @error('usaha_tani') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-control">
                            <label class="label" for="email">
                                <span class="label-text font-semibold">Email <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="email" type="email" id="email" placeholder="contoh@email.com"
                                class="input input-bordered w-full" />
                            @error('email') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Alamat dan Kontak --}}
                    <div class="divider text-blue-800 font-bold">
                        <i class="fas fa-map-marker-alt mr-1"></i> ALAMAT DAN KONTAK
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Alamat Lengkap --}}
                        <div class="form-control md:col-span-2">
                            <label class="label" for="alamat_lengkap">
                                <span class="label-text font-semibold">Alamat Lengkap <span class="text-red-500">*</span></span>
                            </label>
                            <textarea wire:model="alamat_lengkap" id="alamat_lengkap" rows="3"
                                placeholder="Masukkan alamat lengkap"
                                class="textarea textarea-bordered w-full"></textarea>
                            @error('alamat_lengkap') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nama Poktan --}}
                        <div class="form-control">
                            <label class="label" for="nama_poktan">
                                <span class="label-text font-semibold">Nama Poktan <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="nama_poktan" type="text" id="nama_poktan" placeholder="Contoh: Poktan Makmur"
                                class="input input-bordered w-full" />
                            @error('nama_poktan') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- Alamat Poktan --}}
                        <div class="form-control">
                            <label class="label" for="alamat_poktan">
                                <span class="label-text font-semibold">Alamat Poktan <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="alamat_poktan" type="text" id="alamat_poktan" placeholder="Masukkan alamat poktan"
                                class="input input-bordered w-full" />
                            @error('alamat_poktan') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIP (opsional) --}}
                        <div class="form-control">
                            <label class="label" for="nip">
                                <span class="label-text font-semibold">NIP <span class="text-gray-400 text-xs">(opsional)</span></span>
                            </label>
                            <input wire:model="nip" type="text" id="nip" placeholder="Masukkan NIP jika ada"
                                class="input input-bordered w-full" />
                            @error('nip') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="divider text-blue-800 font-bold">
                        <i class="fas fa-lock mr-1"></i> AKUN LOGIN
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label" for="password">
                                <span class="label-text font-semibold">Password <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="password" type="password" id="password" placeholder="Minimal 6 karakter"
                                class="input input-bordered w-full" />
                            @error('password') <span class="label-text-alt text-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label" for="password_confirmation">
                                <span class="label-text font-semibold">Ulangi Password <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                placeholder="Ulangi password"
                                class="input input-bordered w-full" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center pt-4">
                        <button type="submit" class="btn btn-primary btn-wide text-white">
                            <i class="fas fa-paper-plane mr-2"></i>
                            KIRIM PENDAFTARAN
                        </button>
                        <p class="text-xs text-gray-500 mt-2">
                            Dengan mengirim formulir ini, saya menyetujui semua syarat dan ketentuan yang berlaku
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- CEK NIK -->
        <div class="card bg-white shadow-lg border border-red-200 mb-8" x-data="cekNik()">
            <div class="card-body">
                <h2 class="card-title text-red-800 text-lg mb-4">
                    <i class="fa fa-search"></i>
                    CEK NIK ANDA!
                </h2>

                <div class="flex gap-2">
                    <input type="text" maxlength="16" class="input input-bordered flex-1"
                        placeholder="Masukkan NIK Anda" x-model="nik">
                    <button @click="check()" class="btn btn-error text-white" :disabled="loading">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-search"></i>
                                <span class="hidden sm:inline">CEK NIK</span>
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <i class="fa fa-spinner fa-spin"></i>
                                <span class="hidden sm:inline">Memeriksa...</span>
                            </span>
                        </template>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <!-- NIK ditemukan -->
                    <template x-if="result">
                        <div class="alert alert-success">
                            <div>
                                <h3 class="font-bold flex items-center">
                                    <i class="fa fa-check-circle mr-2"></i>
                                    NIK Ditemukan!
                                </h3>
                                <p class="mt-2"><strong>Nama:</strong> <span x-text="result.nama"></span></p>
                                <p><strong>NIK:</strong> <span x-text="result.nik"></span></p>
                                <p><strong>Alamat:</strong> <span x-text="result.alamat"></span></p>
                                <p><strong>Poktan:</strong> <span x-text="result.poktan"></span></p>

                                <!-- Daftar Kegiatan -->
                                <template x-if="result.kegiatan && result.kegiatan.length > 0">
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <h4 class="font-semibold mb-1 flex items-center">
                                            <i class="fa fa-list mr-2"></i> Kegiatan yang Pernah Diikuti
                                        </h4>
                                        <ul class="list-disc list-inside text-sm">
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
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-yellow-800 font-semibold text-sm">
                                            Peserta belum mengikuti kegiatan apa pun.
                                        </p>
                                    </div>
                                </template>

                                <!-- FORM DAFTAR PELATIHAN -->
                                <div class="mt-4 p-4 bg-white border border-gray-200 rounded-lg">
                                    <h3 class="font-semibold mb-3 flex items-center">
                                        <i class="fa fa-edit mr-2 text-blue-600"></i>
                                        Daftar Pelatihan Baru
                                    </h3>

                                    <div class="space-y-3">
                                        <div class="form-control">
                                            <label class="label">
                                                <span class="label-text font-medium">Pilih Jenis Pelatihan</span>
                                            </label>
                                            <select x-model="kegiatan_id" class="select select-bordered w-full">
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

                                        <button @click="daftarPelatihan()"
                                            class="btn btn-primary btn-sm text-white">
                                            <i class="fa fa-paper-plane"></i>
                                            Daftar Pelatihan
                                        </button>

                                        <template x-if="notif">
                                            <div class="alert alert-success py-2" x-text="notif"></div>
                                        </template>

                                        <template x-if="notif_error">
                                            <div class="alert alert-error py-2" x-text="notif_error"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- NIK tidak ditemukan -->
                    <template x-if="error">
                        <div class="alert alert-error">
                            <i class="fa fa-times-circle"></i>
                            <span x-text="error"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Informasi Fasilitas -->
        <div class="card bg-white shadow-lg border border-gray-200 mb-8">
            <div class="card-body">
                <h2 class="card-title text-blue-800 text-lg mb-4">
                    <i class="fas fa-gift"></i>
                    FASILITAS PESERTA
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <ul class="text-sm list-disc list-inside space-y-1">
                        @if (!empty($pengaturan->fasilitas))
                            @foreach ($pengaturan->fasilitas as $item)
                                <li>{{ $item['nama'] ?? $item }}</li>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Tidak ada fasilitas tersedia.</p>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Informasi Lokasi -->
        <div class="card bg-white shadow-lg border border-gray-200 mb-8">
            <div class="card-body">
                <h2 class="card-title text-blue-800 text-lg mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                    LOKASI PELATIHAN
                </h2>
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
