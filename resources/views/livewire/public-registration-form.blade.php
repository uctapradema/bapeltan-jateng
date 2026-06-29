<div>
    <div class="max-w-6xl mx-auto">

        {{-- Hero Info --}}
        <div class="text-center mb-12 animate-in">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-5 py-2.5 rounded-full text-sm font-semibold mb-5 border border-blue-100">
                <i class="fas fa-graduation-cap"></i>
                Pendaftaran {{ date('Y') }}
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight leading-tight">
                {{ $pengaturan->judul ?? 'FORMULIR PENDAFTARAN' }}
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                {{ $pengaturan->sub_judul ?? '' }}
            </p>
            @if($pengaturan && $pengaturan->tanggal_tutup)
            <div class="mt-5 inline-flex items-center gap-2.5 bg-amber-50 text-amber-700 px-5 py-2.5 rounded-xl text-sm font-semibold border border-amber-200">
                <i class="fas fa-clock"></i>
                Batas Pendaftaran: {{ \Carbon\Carbon::parse($pengaturan->tanggal_tutup)->translatedFormat('d MMMM Y') }}
            </div>
            @endif
        </div>

        {{-- Alert Messages --}}
        @if (session()->has('success'))
            <div class="alert alert-success mb-8 shadow-sm">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-error mb-8 shadow-sm">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Two Column Layout --}}
        <div class="grid lg:grid-cols-5 gap-10">

            {{-- Main Form (3 cols) --}}
            <div class="lg:col-span-3">

                <div class="form-card animate-in delay-1">
                    <form wire:submit="create">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold">FORMULIR PENDAFTARAN</h2>
                                <p class="text-blue-200/80 text-sm mt-0.5">Lengkapi semua data dengan benar</p>
                            </div>
                        </div>

                        <div class="p-8 lg:p-10">

                            {{-- Syarat & Ketentuan --}}
                            <div class="mb-10 p-5 bg-blue-50/60 border border-blue-100 rounded-2xl">
                                <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2.5 text-sm">
                                    <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-blue-600 text-xs"></i>
                                    </div>
                                    Syarat dan Ketentuan
                                </h3>
                                <ul class="text-sm text-blue-800 space-y-2 ml-9">
                                    @if (!empty($pengaturan->persyaratan))
                                        @foreach ($pengaturan->persyaratan as $item)
                                            <li class="flex items-start gap-2.5">
                                                <i class="fas fa-check-circle text-blue-400 text-xs mt-1 flex-shrink-0"></i>
                                                <span>{{ $item['nama'] ?? $item }}</span>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-400">Tidak ada persyaratan tersedia.</li>
                                    @endif
                                </ul>
                            </div>

                            {{-- STEP 1 --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="step-badge">1</div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">DATA PERSONAL</h3>
                                        <p class="text-gray-400 text-sm">Identitas diri peserta</p>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent ml-2"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                                    {{-- Kabupaten --}}
                                    <div class="form-control" x-data="searchableSelect()">
                                        <label class="label py-1.5">
                                            <span class="label-text font-semibold text-gray-700">Kabupaten / Kota</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text"
                                                class="input input-bordered w-full pr-10 bg-gray-50/50"
                                                placeholder="Ketik nama kabupaten..."
                                                x-model="query"
                                                @focus="open = true"
                                                @click.outside="open = false"
                                                autocomplete="off" />
                                            <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                                            <div x-show="open &amp;&amp; filtered.length > 0"
                                                x-transition
                                                class="absolute z-50 w-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl max-h-64 overflow-y-auto">
                                                <template x-for="item in filtered" :key="item.id">
                                                    <div class="px-4 py-3 cursor-pointer hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0"
                                                        :class="{ 'bg-blue-50 text-blue-700 font-medium': selectedId == item.id }"
                                                        @click="select(item)">
                                                        <span x-text="item.nama" class="text-sm"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <input type="hidden" wire:model="kabupaten_id" :value="selectedId" />
                                        @error('kabupaten_id') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- NIK --}}
                                    <div class="form-control">
                                        <label class="label py-1.5" for="nik">
                                            <span class="label-text font-semibold text-gray-700">Nomor Induk Kependudukan</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="nik" type="text" id="nik" maxlength="16"
                                                placeholder="16 digit NIK"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('nik') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Nama Lengkap --}}
                                    <div class="form-control md:col-span-2">
                                        <label class="label py-1.5" for="nama">
                                            <span class="label-text font-semibold text-gray-700">Nama Lengkap</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="nama" type="text" id="nama"
                                                placeholder="Sesuai KTP (huruf kapital)"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('nama') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Tempat Lahir --}}
                                    <div class="form-control">
                                        <label class="label py-1.5" for="tempat_lahir">
                                            <span class="label-text font-semibold text-gray-700">Tempat Lahir</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="tempat_lahir" type="text" id="tempat_lahir"
                                                placeholder="Kota / Kabupaten lahir"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('tempat_lahir') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="form-control">
                                        <label class="label py-1.5" for="tanggal_lahir">
                                            <span class="label-text font-semibold text-gray-700">Tanggal Lahir</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="tanggal_lahir" type="date" id="tanggal_lahir"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('tanggal_lahir') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div class="form-control">
                                        <label class="label py-1.5" for="jenis_kelamin">
                                            <span class="label-text font-semibold text-gray-700">Jenis Kelamin</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <select wire:model="jenis_kelamin" id="jenis_kelamin" class="select select-bordered w-full bg-gray-50/50">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Nomor Telepon --}}
                                    <div class="form-control">
                                        <label class="label py-1.5" for="nomor_telepon">
                                            <span class="label-text font-semibold text-gray-700">Nomor Telepon / WA</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="nomor_telepon" type="tel" id="nomor_telepon"
                                                placeholder="08xxxxxxxxxx"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('nomor_telepon') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 2 --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="step-badge">2</div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">DATA TAMBAHAN</h3>
                                        <p class="text-gray-400 text-sm">Informasi pendukung</p>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent ml-2"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <div class="form-control">
                                        <label class="label py-1.5" for="agama">
                                            <span class="label-text font-semibold text-gray-700">Agama</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <select wire:model="agama" id="agama" class="select select-bordered w-full bg-gray-50/50">
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        @error('agama') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="status_pernikahan">
                                            <span class="label-text font-semibold text-gray-700">Status Pernikahan</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <select wire:model="status_pernikahan" id="status_pernikahan" class="select select-bordered w-full bg-gray-50/50">
                                            <option value="">Pilih Status</option>
                                            <option value="Belum Kawin">Belum Kawin</option>
                                            <option value="Kawin">Kawin</option>
                                            <option value="Cerai Hidup">Cerai Hidup</option>
                                            <option value="Cerai Mati">Cerai Mati</option>
                                        </select>
                                        @error('status_pernikahan') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="pendidikan_terakhir">
                                            <span class="label-text font-semibold text-gray-700">Pendidikan Terakhir</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <select wire:model="pendidikan_terakhir" id="pendidikan_terakhir" class="select select-bordered w-full bg-gray-50/50">
                                            <option value="">Pilih Pendidikan</option>
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
                                        @error('pendidikan_terakhir') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="pekerjaan">
                                            <span class="label-text font-semibold text-gray-700">Pekerjaan</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="pekerjaan" type="text" id="pekerjaan"
                                                placeholder="Contoh: Petani"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-briefcase absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('pekerjaan') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="usaha_tani">
                                            <span class="label-text font-semibold text-gray-700">Komoditas / Usaha Tani</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="usaha_tani" type="text" id="usaha_tani"
                                                placeholder="Contoh: Padi, Jagung"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-seedling absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('usaha_tani') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="email">
                                            <span class="label-text font-semibold text-gray-700">Email</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="email" type="email" id="email"
                                                placeholder="contoh@email.com"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('email') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 3 --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="step-badge">3</div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">ALAMAT & KELEMBAGAAN</h3>
                                        <p class="text-gray-400 text-sm">Domisili dan keanggotaan poktan</p>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent ml-2"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <div class="form-control md:col-span-2">
                                        <label class="label py-1.5" for="alamat_lengkap">
                                            <span class="label-text font-semibold text-gray-700">Alamat Lengkap</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <textarea wire:model="alamat_lengkap" id="alamat_lengkap" rows="3"
                                                placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan..."
                                                class="textarea textarea-bordered w-full bg-gray-50/50 pl-10"></textarea>
                                            <i class="fas fa-home absolute left-3.5 top-3.5 text-gray-300"></i>
                                        </div>
                                        @error('alamat_lengkap') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="nama_poktan">
                                            <span class="label-text font-semibold text-gray-700">Nama Poktan</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="nama_poktan" type="text" id="nama_poktan"
                                                placeholder="Contoh: Poktan Makmur"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-users absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('nama_poktan') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="alamat_poktan">
                                            <span class="label-text font-semibold text-gray-700">Alamat Poktan</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="alamat_poktan" type="text" id="alamat_poktan"
                                                placeholder="Alamat kelompok tani"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-map-marker-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('alamat_poktan') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="nip">
                                            <span class="label-text font-semibold text-gray-700">NIP</span>
                                            <span class="label-text-alt text-gray-400 text-xs">(opsional)</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="nip" type="text" id="nip"
                                                placeholder="Masukkan NIP jika ada"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-shield-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('nip') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 4 --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="step-badge">4</div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">AKUN LOGIN</h3>
                                        <p class="text-gray-400 text-sm">Buat akun untuk masuk panel peserta</p>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent ml-2"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <div class="form-control">
                                        <label class="label py-1.5" for="password">
                                            <span class="label-text font-semibold text-gray-700">Password</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="password" type="password" id="password"
                                                placeholder="Minimal 6 karakter"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                        @error('password') <span class="label-text-alt text-error text-xs mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-control">
                                        <label class="label py-1.5" for="password_confirmation">
                                            <span class="label-text font-semibold text-gray-700">Ulangi Password</span>
                                            <span class="label-text-alt text-red-400 text-xs">*wajib</span>
                                        </label>
                                        <div class="relative">
                                            <input wire:model="password_confirmation" type="password" id="password_confirmation"
                                                placeholder="Ulangi password"
                                                class="input input-bordered w-full bg-gray-50/50 pl-10" />
                                            <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex flex-col items-center gap-4 pt-6 border-t border-gray-100">
                                <button type="submit" class="btn-submit flex items-center gap-2.5">
                                    <i class="fas fa-paper-plane"></i>
                                    KIRIM PENDAFTARAN
                                </button>
                                <p class="text-sm text-gray-400 text-center max-w-md">
                                    Dengan mengirim formulir ini, saya menyetujui semua syarat dan ketentuan yang berlaku
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar (2 cols) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Cek NIK --}}
                <div class="form-card animate-in delay-2" x-data="cekNik()">
                    <div class="section-header" style="background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 100%);">
                        <div class="icon-box">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold">CEK NIK</h2>
                            <p class="text-red-200/80 text-xs mt-0.5">Periksa data keikutsertaan</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <input type="text" maxlength="16" class="input input-bordered w-full pl-10 bg-gray-50/50"
                                    placeholder="Masukkan NIK" x-model="nik">
                                <i class="fas fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>
                            <button @click="check()" class="btn btn-error text-white px-5" :disabled="loading">
                                <template x-if="!loading"><i class="fas fa-search"></i></template>
                                <template x-if="loading"><i class="fas fa-spinner fa-spin"></i></template>
                            </button>
                        </div>

                        <div class="mt-5 space-y-4">
                            <template x-if="result">
                                <div class="space-y-4">
                                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                                        <h3 class="font-bold text-green-800 flex items-center gap-2 mb-3">
                                            <i class="fas fa-check-circle"></i> NIK Ditemukan
                                        </h3>
                                        <div class="space-y-1.5 text-sm text-green-700">
                                            <p><span class="font-semibold">Nama:</span> <span x-text="result.nama"></span></p>
                                            <p><span class="font-semibold">NIK:</span> <span x-text="result.nik"></span></p>
                                            <p><span class="font-semibold">Alamat:</span> <span x-text="result.alamat"></span></p>
                                            <p><span class="font-semibold">Poktan:</span> <span x-text="result.poktan"></span></p>
                                        </div>
                                    </div>

                                    <template x-if="result.kegiatan &amp;&amp; result.kegiatan.length > 0">
                                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                            <h4 class="font-semibold text-blue-900 text-sm mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-list-ul"></i> Riwayat Kegiatan
                                            </h4>
                                            <ul class="text-sm text-blue-800 space-y-1.5">
                                                <template x-for="kg in result.kegiatan" :key="kg.kode">
                                                    <li class="flex items-start gap-2">
                                                        <i class="fas fa-dot-circle text-blue-400 mt-1 text-[8px]"></i>
                                                        <span><strong x-text="kg.nama"></strong> (<span x-text="kg.kode"></span>) <span x-text="kg.mulai"></span> - <span x-text="kg.selesai"></span></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>

                                    <template x-if="result.kegiatan &amp;&amp; result.kegiatan.length === 0">
                                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                            <p class="text-yellow-700 font-semibold text-sm flex items-center gap-2">
                                                <i class="fas fa-info-circle"></i> Belum ada riwayat kegiatan.
                                            </p>
                                        </div>
                                    </template>

                                    <div class="p-5 bg-white border border-gray-200 rounded-xl">
                                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-edit text-blue-600 text-xs"></i>
                                            </div>
                                            Daftar Pelatihan Baru
                                        </h3>

                                        <div class="form-control mb-4">
                                            <select x-model="kegiatan_id" class="select select-bordered w-full bg-gray-50/50">
                                                <option value="">-- Pilih Pelatihan --</option>
                                                @foreach (\App\Models\Kegiatan::aktif()->get() as $kg)
                                                    <option value="{{ $kg->id }}">
                                                        {{ $kg->kode_pelatihan }} - {{ $kg->nama_pelatihan }}
                                                        ({{ $kg->tanggal_mulai->format('d M') }} - {{ $kg->tanggal_selesai->format('d M Y') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button @click="daftarPelatihan()" class="btn btn-primary w-full text-white">
                                            <i class="fas fa-paper-plane"></i> Daftar Pelatihan
                                        </button>

                                        <template x-if="notif">
                                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm text-center font-medium" x-text="notif"></div>
                                        </template>
                                        <template x-if="notif_error">
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm text-center font-medium" x-text="notif_error"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="error">
                                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                                    <p class="text-red-700 text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-times-circle"></i> <span x-text="error"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="form-card animate-in delay-3">
                    <div class="p-6">
                        <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-gift text-green-600"></i>
                            </div>
                            Fasilitas Peserta
                        </h3>
                        <div class="space-y-3">
                            @if (!empty($pengaturan->fasilitas))
                                @foreach ($pengaturan->fasilitas as $item)
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <div class="w-6 h-6 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-check text-green-500 text-xs"></i>
                                        </div>
                                        <span>{{ $item['nama'] ?? $item }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400">Tidak ada fasilitas tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="form-card animate-in delay-4">
                    <div class="p-6">
                        <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-red-500"></i>
                            </div>
                            Lokasi Pelatihan
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <p class="text-gray-700 font-medium mb-2.5">
                                {{ $pengaturan->info ?? '' }}
                            </p>
                            <p class="text-gray-500 text-sm flex items-start gap-2.5">
                                <i class="fas fa-location-dot text-red-400 mt-0.5"></i>
                                <span>{{ $pengaturan->lokasi ?? '' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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
                    }
                }
            }
        }

        function searchableSelect() {
            return {
                open: false,
                query: '',
                selectedId: '',
                items: [
                    @foreach(\App\Models\Kabupaten::orderBy('nama')->get() as $kab)
                        { id: {{ $kab->id }}, nama: '{{ $kab->nama }}' },
                    @endforeach
                ],
                get filtered() {
                    if (!this.query) return this.items;
                    return this.items.filter(i => i.nama.toLowerCase().includes(this.query.toLowerCase()));
                },
                select(item) {
                    this.selectedId = item.id;
                    this.query = item.nama;
                    this.open = false;
                }
            }
        }
    </script>
</div>
