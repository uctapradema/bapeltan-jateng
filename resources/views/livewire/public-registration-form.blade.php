<div>
    <div class="max-w-6xl mx-auto">

        {{-- Hero --}}
        <div class="text-center mb-14 anim-in">
            <div class="inline-flex items-center gap-2 bg-sky-50 text-sky-700 px-5 py-2 rounded-full text-sm font-semibold mb-5 border border-sky-100">
                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                Pendaftaran {{ date('Y') }}
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight mb-4" style="color: var(--primary); font-family: 'Lexend', sans-serif">
                {{ $pengaturan->judul ?? 'FORMULIR PENDAFTARAN' }}
            </h1>
            <p class="text-lg max-w-2xl mx-auto leading-relaxed" style="color: #64748B">
                {{ $pengaturan->sub_judul ?? '' }}
            </p>
            @if($pengaturan && $pengaturan->tanggal_tutup)
            <div class="mt-5 inline-flex items-center gap-2.5 bg-amber-50 text-amber-700 px-5 py-2.5 rounded-xl text-sm font-semibold border border-amber-200">
                <i class="fas fa-clock" aria-hidden="true"></i>
                Batas Pendaftaran: {{ \Carbon\Carbon::parse($pengaturan->tanggal_tutup)->format('d-m-Y') }}
            </div>
            @endif
        </div>

        {{-- Alerts --}}
        @if (session()->has('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-8" role="alert">
                <i class="fas fa-check-circle text-lg" aria-hidden="true"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-8" role="alert">
                <i class="fas fa-exclamation-circle text-lg" aria-hidden="true"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Layout: Form (3) + Sidebar (2) --}}
        <div class="grid lg:grid-cols-5 gap-10">

            {{-- Form --}}
            <div class="lg:col-span-3">
                <div class="card-pro anim-in d1">
                    <form wire:submit="create">
                        <div class="section-hdr">
                            <div class="icon-box"><i class="fas fa-user-plus" aria-hidden="true"></i></div>
                            <div>
                                <h2 class="text-lg font-bold" style="font-family: 'Lexend', sans-serif">FORMULIR PENDAFTARAN</h2>
                                <p class="text-sky-200 text-sm mt-0.5">Lengkapi semua data dengan benar</p>
                            </div>
                        </div>

                        <div class="p-8 lg:p-12">

                            {{-- Syarat --}}
                            <div class="mb-12 p-6 rounded-xl border" style="background: #EFF6FF; border-color: #BFDBFE;">
                                <h3 class="font-bold mb-4 flex items-center gap-2.5" style="color: #1E3A8A">
                                    <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #DBEAFE">
                                        <i class="fas fa-clipboard-check text-xs" aria-hidden="true"></i>
                                    </span>
                                    Syarat dan Ketentuan
                                </h3>
                                <ul class="text-sm space-y-2 ml-9" style="color: #1E40AF">
                                    @if (!empty($pengaturan->persyaratan))
                                        @foreach ($pengaturan->persyaratan as $item)
                                            <li class="flex items-start gap-2.5">
                                                <i class="fas fa-check-circle text-xs mt-1 flex-shrink-0 opacity-60" aria-hidden="true"></i>
                                                <span>{{ $item['nama'] ?? $item }}</span>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="opacity-60">Tidak ada persyaratan tersedia.</li>
                                    @endif
                                </ul>
                            </div>

                            {{-- STEP 1 --}}
                            <div class="mb-12">
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="step-num">1</div>
                                    <div>
                                        <h3 class="font-bold" style="color: var(--primary); font-family: 'Lexend', sans-serif">DATA PERSONAL</h3>
                                        <p class="text-sm" style="color: #94A3B8">Identitas diri peserta</p>
                                    </div>
                                    <div class="flex-1 h-px ml-2" style="background: linear-gradient(to right, #E2E8F0, transparent)"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">

                                    {{-- Kabupaten --}}
                                    <div x-data="searchableSelect()">
                                        <label class="label-pro">Kabupaten / Kota <span class="req">*</span></label>
                                        {{-- Hidden input sebagai jembatan wire:model --}}
                                        <input type="hidden" wire:model="kabupaten_id" x-ref="kabHidden" />
                                        <div class="relative">
                                            <input type="text" class="input-pro" style="padding-right: 40px"
                                                placeholder="Ketik nama kabupaten..."
                                                x-model="query"
                                                @focus="open = true"
                                                @click.outside="open = false"
                                                autocomplete="off"
                                                aria-label="Cari kabupaten" />
                                            <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" style="color: #94A3B8" aria-hidden="true"></i>
                                            <div x-show="open && filtered.length > 0"
                                                x-transition
                                                class="absolute z-50 w-full mt-1.5 bg-white border rounded-xl shadow-xl max-h-64 overflow-y-auto"
                                                style="border-color: #E2E8F0"
                                                role="listbox">
                                                <template x-for="item in filtered" :key="item.id">
                                                    <div class="px-4 py-3 cursor-pointer transition-colors border-b last:border-0"
                                                        style="border-color: #F1F5F9"
                                                        :class="{ 'font-semibold': selectedId == item.id }"
                                                        :style="selectedId == item.id ? 'background: #EFF6FF; color: #0369A1' : ''"
                                                        @mousedown.prevent="
                                                            selectedId = item.id;
                                                            query = item.nama;
                                                            open = false;
                                                            $refs.kabHidden.value = item.id;
                                                            $refs.kabHidden.dispatchEvent(new Event('input'));
                                                        "
                                                        role="option"
                                                        :aria-selected="selectedId == item.id">
                                                        <span x-text="item.nama" class="text-sm"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @error('kabupaten_id') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- NIK --}}
                                    <div>
                                        <label class="label-pro" for="nik">Nomor Induk Kependudukan <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-id-card icon-el" aria-hidden="true"></i>
                                            <input wire:model="nik" type="text" id="nik" maxlength="16" class="input-pro has-icon" placeholder="16 digit NIK" />
                                        </div>
                                        @error('nik') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Nama --}}
                                    <div class="md:col-span-2">
                                        <label class="label-pro" for="nama">Nama Lengkap <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-user icon-el" aria-hidden="true"></i>
                                            <input wire:model="nama" type="text" id="nama" class="input-pro has-icon" placeholder="Sesuai KTP (huruf kapital)" />
                                        </div>
                                        @error('nama') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Tempat Lahir --}}
                                    <div>
                                        <label class="label-pro" for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-map-pin icon-el" aria-hidden="true"></i>
                                            <input wire:model="tempat_lahir" type="text" id="tempat_lahir" class="input-pro has-icon" placeholder="Kota / Kabupaten lahir" />
                                        </div>
                                        @error('tempat_lahir') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div>
                                        <label class="label-pro" for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-calendar icon-el" aria-hidden="true"></i>
                                            <input wire:model="tanggal_lahir" type="date" id="tanggal_lahir" class="input-pro has-icon" />
                                        </div>
                                        @error('tanggal_lahir') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div>
                                        <label class="label-pro" for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                                        <select wire:model="jenis_kelamin" id="jenis_kelamin" class="select-pro">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Telepon --}}
                                    <div>
                                        <label class="label-pro" for="nomor_telepon">Nomor Telepon / WA <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-phone icon-el" aria-hidden="true"></i>
                                            <input wire:model="nomor_telepon" type="tel" id="nomor_telepon" class="input-pro has-icon" placeholder="08xxxxxxxxxx" />
                                        </div>
                                        @error('nomor_telepon') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 2 --}}
                            <div class="mb-12">
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="step-num">2</div>
                                    <div>
                                        <h3 class="font-bold" style="color: var(--primary); font-family: 'Lexend', sans-serif">DATA TAMBAHAN</h3>
                                        <p class="text-sm" style="color: #94A3B8">Informasi pendukung</p>
                                    </div>
                                    <div class="flex-1 h-px ml-2" style="background: linear-gradient(to right, #E2E8F0, transparent)"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                    <div>
                                        <label class="label-pro" for="agama">Agama <span class="req">*</span></label>
                                        <select wire:model="agama" id="agama" class="select-pro">
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        @error('agama') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="status_pernikahan">Status Pernikahan <span class="req">*</span></label>
                                        <select wire:model="status_pernikahan" id="status_pernikahan" class="select-pro">
                                            <option value="">Pilih Status</option>
                                            <option value="Belum Kawin">Belum Kawin</option>
                                            <option value="Kawin">Kawin</option>
                                            <option value="Cerai Hidup">Cerai Hidup</option>
                                            <option value="Cerai Mati">Cerai Mati</option>
                                        </select>
                                        @error('status_pernikahan') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="pendidikan_terakhir">Pendidikan Terakhir <span class="req">*</span></label>
                                        <select wire:model="pendidikan_terakhir" id="pendidikan_terakhir" class="select-pro">
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
                                        @error('pendidikan_terakhir') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="pekerjaan">Pekerjaan <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-briefcase icon-el" aria-hidden="true"></i>
                                            <input wire:model="pekerjaan" type="text" id="pekerjaan" class="input-pro has-icon" placeholder="Contoh: Petani" />
                                        </div>
                                        @error('pekerjaan') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="usaha_tani">Komoditas / Usaha Tani <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-seedling icon-el" aria-hidden="true"></i>
                                            <input wire:model="usaha_tani" type="text" id="usaha_tani" class="input-pro has-icon" placeholder="Contoh: Padi, Jagung" />
                                        </div>
                                        @error('usaha_tani') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="email">Email <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-envelope icon-el" aria-hidden="true"></i>
                                            <input wire:model="email" type="email" id="email" class="input-pro has-icon" placeholder="contoh@email.com" />
                                        </div>
                                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 3 --}}
                            <div class="mb-12">
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="step-num">3</div>
                                    <div>
                                        <h3 class="font-bold" style="color: var(--primary); font-family: 'Lexend', sans-serif">ALAMAT & KELEMBAGAAN</h3>
                                        <p class="text-sm" style="color: #94A3B8">Domisili dan keanggotaan poktan</p>
                                    </div>
                                    <div class="flex-1 h-px ml-2" style="background: linear-gradient(to right, #E2E8F0, transparent)"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                    <div class="md:col-span-2">
                                        <label class="label-pro" for="alamat_lengkap">Alamat Lengkap <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-home icon-el" style="top: 14px; transform: none" aria-hidden="true"></i>
                                            <textarea wire:model="alamat_lengkap" id="alamat_lengkap" rows="3" class="textarea-pro" style="padding-left: 40px" placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan..."></textarea>
                                        </div>
                                        @error('alamat_lengkap') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="nama_poktan">Nama Poktan <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-users icon-el" aria-hidden="true"></i>
                                            <input wire:model="nama_poktan" type="text" id="nama_poktan" class="input-pro has-icon" placeholder="Contoh: Poktan Makmur" />
                                        </div>
                                        @error('nama_poktan') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="alamat_poktan">Alamat Poktan <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-map-marker-alt icon-el" aria-hidden="true"></i>
                                            <input wire:model="alamat_poktan" type="text" id="alamat_poktan" class="input-pro has-icon" placeholder="Alamat kelompok tani" />
                                        </div>
                                        @error('alamat_poktan') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="nip">NIP <span class="opt">(opsional)</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-shield-alt icon-el" aria-hidden="true"></i>
                                            <input wire:model="nip" type="text" id="nip" class="input-pro has-icon" placeholder="Masukkan NIP jika ada" />
                                        </div>
                                        @error('nip') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 4 --}}
                            <div class="mb-12">
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="step-num">4</div>
                                    <div>
                                        <h3 class="font-bold" style="color: var(--primary); font-family: 'Lexend', sans-serif">AKUN LOGIN</h3>
                                        <p class="text-sm" style="color: #94A3B8">Buat akun untuk masuk panel peserta</p>
                                    </div>
                                    <div class="flex-1 h-px ml-2" style="background: linear-gradient(to right, #E2E8F0, transparent)"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                    <div>
                                        <label class="label-pro" for="password">Password <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-lock icon-el" aria-hidden="true"></i>
                                            <input wire:model="password" type="password" id="password" class="input-pro has-icon" placeholder="Minimal 6 karakter" />
                                        </div>
                                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="label-pro" for="password_confirmation">Ulangi Password <span class="req">*</span></label>
                                        <div class="icon-field">
                                            <i class="fas fa-lock icon-el" aria-hidden="true"></i>
                                            <input wire:model="password_confirmation" type="password" id="password_confirmation" class="input-pro has-icon" placeholder="Ulangi password" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex flex-col items-center gap-4 pt-8 border-t" style="border-color: #E2E8F0">
                                <button type="submit" class="btn-cta">
                                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                    KIRIM PENDAFTARAN
                                </button>
                                <p class="text-sm text-center max-w-md" style="color: #94A3B8">
                                    Dengan mengirim formulir ini, saya menyetujui semua syarat dan ketentuan yang berlaku
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Cek NIK --}}
                <div class="card-pro anim-in d2" x-data="cekNik()">
                    <div class="section-hdr" style="background: linear-gradient(135deg, #7F1D1D, #991B1B)">
                        <div class="icon-box"><i class="fas fa-search" aria-hidden="true"></i></div>
                        <div>
                            <h2 class="text-base font-bold" style="font-family: 'Lexend', sans-serif">CEK NIK</h2>
                            <p class="text-red-200/80 text-xs mt-0.5">Periksa data keikutsertaan</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <input type="text" maxlength="16" class="input-pro has-icon" placeholder="Masukkan NIK" x-model="nik" aria-label="NIK untuk dicek" />
                                <i class="fas fa-id-card icon-el" aria-hidden="true"></i>
                            </div>
                            <button @click="check()" class="btn-outline !text-white !border-red-500 !bg-red-500 hover:!bg-red-600 px-4" :disabled="loading" aria-label="Cek NIK">
                                <template x-if="!loading"><i class="fas fa-search" aria-hidden="true"></i></template>
                                <template x-if="loading"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i></template>
                            </button>
                        </div>

                        <div class="mt-5 space-y-4">
                            <template x-if="result">
                                <div class="space-y-4">
                                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                        <h3 class="font-bold text-emerald-800 flex items-center gap-2 mb-3">
                                            <i class="fas fa-check-circle" aria-hidden="true"></i> NIK Ditemukan
                                        </h3>
                                        <div class="space-y-1.5 text-sm text-emerald-700">
                                            <p><span class="font-semibold">Nama:</span> <span x-text="result.nama"></span></p>
                                            <p><span class="font-semibold">NIK:</span> <span x-text="result.nik"></span></p>
                                            <p><span class="font-semibold">Alamat:</span> <span x-text="result.alamat"></span></p>
                                            <p><span class="font-semibold">Poktan:</span> <span x-text="result.poktan"></span></p>
                                        </div>
                                    </div>

                                    <template x-if="result.kegiatan &amp;&amp; result.kegiatan.length > 0">
                                        <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl">
                                            <h4 class="font-semibold text-sky-900 text-sm mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-list-ul" aria-hidden="true"></i> Riwayat Kegiatan
                                            </h4>
                                            <ul class="text-sm text-sky-800 space-y-1.5">
                                                <template x-for="kg in result.kegiatan" :key="kg.kode">
                                                    <li class="flex items-start gap-2">
                                                        <i class="fas fa-dot-circle text-sky-400 mt-1 text-[8px]" aria-hidden="true"></i>
                                                        <span><strong x-text="kg.nama"></strong> (<span x-text="kg.kode"></span>) <span x-text="kg.mulai"></span> - <span x-text="kg.selesai"></span></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>

                                    <template x-if="result.kegiatan &amp;&amp; result.kegiatan.length === 0">
                                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                            <p class="text-amber-700 font-semibold text-sm flex items-center gap-2">
                                                <i class="fas fa-info-circle" aria-hidden="true"></i> Belum ada riwayat kegiatan.
                                            </p>
                                        </div>
                                    </template>

                                    <div class="p-5 bg-white border rounded-xl" style="border-color: #E2E8F0">
                                        <h3 class="font-bold mb-4 flex items-center gap-2" style="color: var(--primary)">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #EFF6FF">
                                                <i class="fas fa-edit text-xs" style="color: #0369A1" aria-hidden="true"></i>
                                            </span>
                                            Daftar Pelatihan Baru
                                        </h3>

                                        <div class="mb-4">
                                            <select x-model="kegiatan_id" class="select-pro">
                                                <option value="">-- Pilih Pelatihan --</option>
                                                @foreach (\App\Models\Kegiatan::aktif()->get() as $kg)
                                                    <option value="{{ $kg->id }}">
                                                        {{ $kg->kode_pelatihan }} - {{ $kg->nama_pelatihan }}
                                                        ({{ $kg->tanggal_mulai->format('d M') }} - {{ $kg->tanggal_selesai->format('d M Y') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button @click="daftarPelatihan()" class="btn-cta w-full text-sm !py-3">
                                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Daftar Pelatihan
                                        </button>

                                        <template x-if="notif">
                                            <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm text-center font-medium" x-text="notif" role="status"></div>
                                        </template>
                                        <template x-if="notif_error">
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm text-center font-medium" x-text="notif_error" role="alert"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="error">
                                <div class="p-4 bg-red-50 border border-red-200 rounded-xl" role="alert">
                                    <p class="text-red-700 text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-times-circle" aria-hidden="true"></i> <span x-text="error"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="card-pro anim-in d3">
                    <div class="p-6">
                        <h3 class="font-bold mb-5 flex items-center gap-2.5" style="color: var(--primary)">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-100">
                                <i class="fas fa-gift text-emerald-600" aria-hidden="true"></i>
                            </span>
                            Fasilitas Peserta
                        </h3>
                        <div class="space-y-3">
                            @if (!empty($pengaturan->fasilitas))
                                @foreach ($pengaturan->fasilitas as $item)
                                    <div class="flex items-center gap-3 text-sm" style="color: #475569">
                                        <span class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 bg-emerald-50">
                                            <i class="fas fa-check text-emerald-500 text-xs" aria-hidden="true"></i>
                                        </span>
                                        <span>{{ $item['nama'] ?? $item }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm" style="color: #94A3B8">Tidak ada fasilitas tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="card-pro anim-in d4">
                    <div class="p-6">
                        <h3 class="font-bold mb-5 flex items-center gap-2.5" style="color: var(--primary)">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-100">
                                <i class="fas fa-map-marker-alt text-red-500" aria-hidden="true"></i>
                            </span>
                            Lokasi Pelatihan
                        </h3>
                        <div class="rounded-xl p-5 border" style="background: #F8FAFC; border-color: #E2E8F0">
                            <p class="font-medium mb-2.5" style="color: var(--primary)">
                                {{ $pengaturan->info ?? '' }}
                            </p>
                            <p class="text-sm flex items-start gap-2.5" style="color: #64748B">
                                <i class="fas fa-location-dot text-red-400 mt-0.5" aria-hidden="true"></i>
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
                nik: '', loading: false, result: null, error: null,
                kegiatan_id: '', notif: null, notif_error: null,

                async check() {
                    this.loading = true; this.result = null; this.error = null;
                    try {
                        const r = await fetch(`/api/cek-nik?nik=${this.nik}`);
                        const d = await r.json();
                        d.success ? this.result = d.data : this.error = d.message;
                    } catch (e) { this.error = 'Terjadi kesalahan saat memeriksa NIK.'; }
                    this.loading = false;
                },

                async daftarPelatihan() {
                    this.notif = null; this.notif_error = null;
                    if (!this.kegiatan_id) { this.notif_error = "Silakan pilih pelatihan terlebih dahulu."; return; }
                    try {
                        const r = await fetch('/api/daftar-pelatihan', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ nik: this.nik, kegiatan_id: this.kegiatan_id })
                        });
                        const d = await r.json();
                        d.success ? this.notif = d.message : this.notif_error = d.message;
                    } catch (e) { this.notif_error = "Terjadi kesalahan saat mendaftar pelatihan."; }
                }
            }
        }

        function searchableSelect() {
            const items = @json(\App\Models\Kabupaten::orderBy('nama')->get(['id','nama']));
            const initId = @json($kabupaten_id ?: null);
            const initItem = initId ? items.find(i => i.id == initId) : null;
            return {
                open: false,
                query: initItem ? initItem.nama : '',
                selectedId: initItem ? initItem.id : '',
                items,
                get filtered() {
                    if (!this.query) return this.items;
                    return this.items.filter(i => i.nama.toLowerCase().includes(this.query.toLowerCase()));
                }
            }
        }
    </script>
</div>
