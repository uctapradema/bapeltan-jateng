<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Pelatihan - Bapeltan Jateng</title>

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Source+Sans+3:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            color: #020617;
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 { font-family: 'Lexend', sans-serif; }

        :root {
            --primary: #0F172A;
            --on-primary: #FFFFFF;
            --secondary: #334155;
            --accent: #0369A1;
            --accent-hover: #0284C7;
            --background: #F8FAFC;
            --foreground: #020617;
            --muted: #E8ECF1;
            --border: #CBD5E1;
            --destructive: #DC2626;
            --ring: #0369A1;
            --success: #059669;
            --radius: 12px;
        }

        .hero-bg {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F4C75 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(3,105,161,.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(3,105,161,.1) 0%, transparent 50%);
        }

        .card-pro {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.06);
        }

        .section-hdr {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 28px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .section-hdr .icon-box {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .step-num {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--accent);
            color: white; font-size: 15px; font-weight: 700;
            flex-shrink: 0; font-family: 'Lexend', sans-serif;
        }

        .input-pro {
            width: 100%; height: 48px;
            padding: 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #fff;
            font-size: 16px; color: var(--foreground);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            font-family: 'Source Sans 3', sans-serif;
        }
        .input-pro:focus { border-color: var(--ring); box-shadow: 0 0 0 3px rgba(3,105,161,.15); }
        .input-pro::placeholder { color: #94A3B8; }
        .input-pro.has-icon { padding-left: 44px; }
        .input-pro.is-invalid { border-color: var(--destructive); }

        .select-pro {
            width: 100%; height: 48px;
            padding: 0 44px 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #fff;
            font-size: 16px; color: var(--foreground);
            transition: border-color .2s, box-shadow .2s;
            outline: none; cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            font-family: 'Source Sans 3', sans-serif;
        }
        .select-pro:focus { border-color: var(--ring); box-shadow: 0 0 0 3px rgba(3,105,161,.15); }
        .select-pro.is-invalid { border-color: var(--destructive); }

        .textarea-pro {
            width: 100%; padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px; background: #fff;
            font-size: 16px; color: var(--foreground);
            resize: vertical; min-height: 100px;
            transition: border-color .2s, box-shadow .2s;
            outline: none; font-family: 'Source Sans 3', sans-serif; line-height: 1.6;
        }
        .textarea-pro:focus { border-color: var(--ring); box-shadow: 0 0 0 3px rgba(3,105,161,.15); }
        .textarea-pro.is-invalid { border-color: var(--destructive); }

        .label-pro {
            display: block; margin-bottom: 8px;
            font-size: 15px; font-weight: 600;
            color: var(--secondary); line-height: 1.5;
        }
        .label-pro .req { color: var(--destructive); margin-left: 2px; }
        .label-pro .opt { color: #94A3B8; font-weight: 400; font-size: 14px; margin-left: 4px; }

        .error-msg { display: block; margin-top: 6px; font-size: 14px; color: var(--destructive); }

        .icon-field { position: relative; }
        .icon-field .icon-el {
            position: absolute; left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #94A3B8; font-size: 15px; pointer-events: none;
        }

        .btn-cta {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 10px; height: 52px; padding: 0 48px;
            background: var(--accent); color: white;
            border: none; border-radius: 12px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            transition: all .2s ease; font-family: 'Lexend', sans-serif;
            letter-spacing: .02em;
        }
        .btn-cta:hover { background: var(--accent-hover); box-shadow: 0 4px 16px rgba(3,105,161,.3); }
        .btn-cta:focus-visible { outline: 3px solid var(--ring); outline-offset: 2px; }

        .btn-outline {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 8px; height: 44px; padding: 0 20px;
            background: transparent; color: var(--accent);
            border: 1.5px solid var(--accent); border-radius: 10px;
            font-size: 15px; font-weight: 600; cursor: pointer;
            transition: all .2s ease; font-family: 'Source Sans 3', sans-serif;
        }
        .btn-outline:hover { background: var(--accent); color: white; }

        .footer-pro { background: var(--primary); color: white; }

        /* Searchable select dropdown */
        .kab-wrapper { position: relative; }
        .kab-dropdown {
            display: none;
            position: absolute; z-index: 50;
            width: 100%; margin-top: 6px;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
            max-height: 260px; overflow-y: auto;
        }
        .kab-dropdown.open { display: block; }
        .kab-item {
            padding: 12px 16px; cursor: pointer;
            font-size: 14px; border-bottom: 1px solid #F1F5F9;
            transition: background .15s;
        }
        .kab-item:last-child { border-bottom: none; }
        .kab-item:hover, .kab-item.active { background: #EFF6FF; color: #0369A1; font-weight: 600; }

        @keyframes anim-in { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .anim-in { animation: anim-in .4s ease both; }
        .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; }
        .d3 { animation-delay: .15s; } .d4 { animation-delay: .2s; }
    </style>
</head>

<body class="min-h-screen flex flex-col">

    <a href="#main-content" style="position:absolute;top:-100%;left:16px;background:var(--accent);color:#fff;padding:10px 20px;border-radius:8px;z-index:100;font-weight:600;text-decoration:none">
        Langsung ke konten utama
    </a>

    {{-- Header --}}
    <header class="hero-bg text-white relative z-10">
        <div class="container mx-auto px-6 lg:px-8 py-8 lg:py-10">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-5">
                    <a href="/" class="flex-shrink-0" aria-label="Beranda Bapeltan">
                        <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center p-2.5 border border-white/20">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg/600px-Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg.png"
                                alt="Logo Kementerian Pertanian" class="w-full h-full object-contain">
                        </div>
                    </a>
                </div>
                <div class="text-right">
                    <h1 class="text-xl lg:text-2xl font-bold tracking-tight leading-tight">BALAI PELATIHAN PERTANIAN</h1>
                    <p class="text-sky-200 text-sm lg:text-base font-medium mt-0.5">Provinsi Jawa Tengah</p>
                    <a href="/" class="inline-flex items-center gap-2 mt-3 text-xs lg:text-sm text-sky-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl">
                        <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full block" aria-hidden="true">
                <path d="M0 56L60 50.5C120 45 240 34 360 29.5C480 25 600 27 720 31.5C840 36 960 43 1080 43.5C1200 44 1320 38 1380 35L1440 32V56H0Z" fill="#F8FAFC"/>
            </svg>
        </div>
    </header>

    {{-- Main --}}
    <main id="main-content" class="flex-1 container mx-auto px-6 lg:px-8 py-10 lg:py-14" role="main">
        <div class="max-w-6xl mx-auto">

            {{-- Hero --}}
            <div class="text-center mb-14 anim-in">
                <div class="inline-flex items-center gap-2 bg-sky-50 text-sky-700 px-5 py-2 rounded-full text-sm font-semibold mb-5 border border-sky-100">
                    <i class="fas fa-graduation-cap"></i>
                    Pendaftaran {{ date('Y') }}
                </div>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight mb-4" style="color:var(--primary)">
                    {{ $pengaturan->judul ?? 'FORMULIR PENDAFTARAN' }}
                </h2>
                <p class="text-lg max-w-2xl mx-auto leading-relaxed" style="color:#64748B">
                    {{ $pengaturan->sub_judul ?? '' }}
                </p>
                @if($pengaturan && $pengaturan->tanggal_tutup)
                <div class="mt-5 inline-flex items-center gap-2.5 bg-amber-50 text-amber-700 px-5 py-2.5 rounded-xl text-sm font-semibold border border-amber-200">
                    <i class="fas fa-clock"></i>
                    Batas Pendaftaran: {{ \Carbon\Carbon::parse($pengaturan->tanggal_tutup)->format('d-m-Y') }}
                </div>
                @endif
            </div>

            {{-- Success Modal --}}
            @if(session('success'))
            <div id="successModal" style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px)">
                <div style="background:#fff;border-radius:20px;padding:40px 36px;max-width:420px;width:100%;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.2);animation:pop-in .35s cubic-bezier(.34,1.56,.64,1) both">
                    <div style="width:72px;height:72px;border-radius:50%;background:#D1FAE5;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                        <i class="fas fa-check" style="font-size:32px;color:#059669"></i>
                    </div>
                    <h3 style="font-family:'Lexend',sans-serif;font-size:20px;font-weight:700;color:#0F172A;margin-bottom:10px">Pendaftaran Berhasil!</h3>
                    <p style="color:#475569;font-size:15px;line-height:1.6;margin-bottom:28px">{{ session('success') }}</p>
                    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;height:48px;padding:0 36px;background:#0369A1;color:#fff;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;font-family:'Lexend',sans-serif;transition:background .2s"
                        onmouseover="this.style.background='#0284C7'" onmouseout="this.style.background='#0369A1'">
                        <i class="fas fa-sign-in-alt"></i> Login Sekarang
                    </a>
                    <button onclick="document.getElementById('successModal').remove()" style="display:block;margin:14px auto 0;background:none;border:none;color:#94A3B8;font-size:14px;cursor:pointer;font-family:'Source Sans 3',sans-serif">
                        Tutup
                    </button>
                </div>
            </div>
            <style>
                @keyframes pop-in {
                    from { opacity:0; transform:scale(.85); }
                    to   { opacity:1; transform:scale(1); }
                }
            </style>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-8">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            @endif

            <div class="grid lg:grid-cols-5 gap-10">

                {{-- Form --}}
                <div class="lg:col-span-3">
                    <div class="card-pro anim-in d1">
                        <form action="{{ route('public.registration.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="section-hdr">
                                <div class="icon-box"><i class="fas fa-user-plus"></i></div>
                                <div>
                                    <h2 class="text-lg font-bold">FORMULIR PENDAFTARAN</h2>
                                    <p class="text-sky-200 text-sm mt-0.5">Lengkapi semua data dengan benar</p>
                                </div>
                            </div>

                            <div class="p-8 lg:p-12">

                                {{-- Syarat --}}
                                <div class="mb-12 p-6 rounded-xl border" style="background:#EFF6FF;border-color:#BFDBFE">
                                    <h3 class="font-bold mb-4 flex items-center gap-2.5" style="color:#1E3A8A">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#DBEAFE">
                                            <i class="fas fa-clipboard-check text-xs"></i>
                                        </span>
                                        Syarat dan Ketentuan
                                    </h3>
                                    <ul class="text-sm space-y-2 ml-9" style="color:#1E40AF">
                                        @if(!empty($pengaturan->persyaratan))
                                            @foreach($pengaturan->persyaratan as $item)
                                            <li class="flex items-start gap-2.5">
                                                <i class="fas fa-check-circle text-xs mt-1 flex-shrink-0 opacity-60"></i>
                                                <span>{{ $item['nama'] ?? $item }}</span>
                                            </li>
                                            @endforeach
                                        @else
                                            <li class="opacity-60">Tidak ada persyaratan tersedia.</li>
                                        @endif
                                    </ul>
                                </div>

                                {{-- STEP 1: DATA PERSONAL --}}
                                <div class="mb-12">
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="step-num">1</div>
                                        <div>
                                            <h3 class="font-bold" style="color:var(--primary)">DATA PERSONAL</h3>
                                            <p class="text-sm" style="color:#94A3B8">Identitas diri peserta</p>
                                        </div>
                                        <div class="flex-1 h-px ml-2" style="background:linear-gradient(to right,#E2E8F0,transparent)"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">

                                        {{-- Kabupaten --}}
                                        <div>
                                            <label class="label-pro" for="kabupaten_id">Kabupaten / Kota <span class="req">*</span></label>
                                            <div class="kab-wrapper">
                                                <div class="relative">
                                                    <input type="text" id="kabupatenSearch" class="input-pro {{ $errors->has('kabupaten_id') ? 'is-invalid' : '' }}"
                                                        style="padding-right:40px"
                                                        placeholder="Ketik nama kabupaten..."
                                                        autocomplete="off"
                                                        value="{{ old('kabupaten_id') ? optional(\App\Models\Kabupaten::find(old('kabupaten_id')))->nama : '' }}" />
                                                    <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" style="color:#94A3B8"></i>
                                                </div>
                                                <input type="hidden" name="kabupaten_id" id="kabupaten_id"
                                                    value="{{ old('kabupaten_id') }}">
                                                <div class="kab-dropdown" id="kabDropdown">
                                                    @foreach($kabupatens as $kab)
                                                    <div class="kab-item" data-id="{{ $kab->id }}" data-nama="{{ $kab->nama }}">
                                                        {{ $kab->nama }}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @error('kabupaten_id')
                                            <span class="error-msg">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- NIK --}}
                                        <div>
                                            <label class="label-pro" for="nik">Nomor Induk Kependudukan <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-id-card icon-el"></i>
                                                <input type="text" name="nik" id="nik" maxlength="16"
                                                    class="input-pro has-icon {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                                                    placeholder="16 digit NIK"
                                                    value="{{ old('nik') }}" />
                                            </div>
                                            @error('nik')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- Nama --}}
                                        <div class="md:col-span-2">
                                            <label class="label-pro" for="nama">Nama Lengkap <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-user icon-el"></i>
                                                <input type="text" name="nama" id="nama"
                                                    class="input-pro has-icon {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                                    placeholder="Sesuai KTP (huruf kapital)"
                                                    value="{{ old('nama') }}"
                                                    oninput="this.value=this.value.toUpperCase()" />
                                            </div>
                                            @error('nama')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- Tempat Lahir --}}
                                        <div>
                                            <label class="label-pro" for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-map-pin icon-el"></i>
                                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                                    class="input-pro has-icon {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}"
                                                    placeholder="Kota / Kabupaten lahir"
                                                    value="{{ old('tempat_lahir') }}" />
                                            </div>
                                            @error('tempat_lahir')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- Tanggal Lahir --}}
                                        <div>
                                            <label class="label-pro" for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                class="input-pro {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}"
                                                value="{{ old('tanggal_lahir') }}" />
                                            @error('tanggal_lahir')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- Jenis Kelamin --}}
                                        <div>
                                            <label class="label-pro" for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                                            <select name="jenis_kelamin" id="jenis_kelamin"
                                                class="select-pro {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="LAKI-LAKI" {{ old('jenis_kelamin') === 'LAKI-LAKI' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="PEREMPUAN" {{ old('jenis_kelamin') === 'PEREMPUAN' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- Nomor Telepon --}}
                                        <div>
                                            <label class="label-pro" for="nomor_telepon">Nomor Telepon / WA <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-phone icon-el"></i>
                                                <input type="tel" name="nomor_telepon" id="nomor_telepon"
                                                    class="input-pro has-icon {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}"
                                                    placeholder="08xxxxxxxxxx"
                                                    value="{{ old('nomor_telepon') }}" />
                                            </div>
                                            @error('nomor_telepon')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 2: DATA TAMBAHAN --}}
                                <div class="mb-12">
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="step-num">2</div>
                                        <div>
                                            <h3 class="font-bold" style="color:var(--primary)">DATA TAMBAHAN</h3>
                                            <p class="text-sm" style="color:#94A3B8">Informasi pendukung</p>
                                        </div>
                                        <div class="flex-1 h-px ml-2" style="background:linear-gradient(to right,#E2E8F0,transparent)"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                        <div>
                                            <label class="label-pro" for="agama">Agama <span class="req">*</span></label>
                                            <select name="agama" id="agama" class="select-pro {{ $errors->has('agama') ? 'is-invalid' : '' }}">
                                                <option value="">Pilih Agama</option>
                                                @foreach(['ISLAM'=>'Islam','KRISTEN'=>'Kristen','KATOLIK'=>'Katolik','HINDU'=>'Hindu','BUDDHA'=>'Buddha','KONGHUCU'=>'Konghucu'] as $val => $label)
                                                <option value="{{ $val }}" {{ old('agama') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('agama')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="status_pernikahan">Status Pernikahan <span class="req">*</span></label>
                                            <select name="status_pernikahan" id="status_pernikahan" class="select-pro {{ $errors->has('status_pernikahan') ? 'is-invalid' : '' }}">
                                                <option value="">Pilih Status</option>
                                                @foreach(['BELUM MENIKAH'=>'Belum Menikah','MENIKAH'=>'Menikah','CERAI HIDUP'=>'Cerai Hidup','CERAI MATI'=>'Cerai Mati'] as $val => $label)
                                                <option value="{{ $val }}" {{ old('status_pernikahan') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('status_pernikahan')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="pendidikan_terakhir">Pendidikan Terakhir <span class="req">*</span></label>
                                            <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="select-pro {{ $errors->has('pendidikan_terakhir') ? 'is-invalid' : '' }}">
                                                <option value="">Pilih Pendidikan</option>
                                                @foreach(['SD','SMP','SMA','D1','D2','D3','S1','S2','S3'] as $pend)
                                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir') === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                                @endforeach
                                            </select>
                                            @error('pendidikan_terakhir')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="pekerjaan">Pekerjaan <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-briefcase icon-el"></i>
                                                <input type="text" name="pekerjaan" id="pekerjaan"
                                                    class="input-pro has-icon {{ $errors->has('pekerjaan') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: Petani"
                                                    value="{{ old('pekerjaan') }}" />
                                            </div>
                                            @error('pekerjaan')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="usaha_tani">Komoditas / Usaha Tani <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-seedling icon-el"></i>
                                                <input type="text" name="usaha_tani" id="usaha_tani"
                                                    class="input-pro has-icon {{ $errors->has('usaha_tani') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: Padi, Jagung"
                                                    value="{{ old('usaha_tani') }}" />
                                            </div>
                                            @error('usaha_tani')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="email">Email <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-envelope icon-el"></i>
                                                <input type="email" name="email" id="email"
                                                    class="input-pro has-icon {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                    placeholder="contoh@email.com"
                                                    value="{{ old('email') }}" />
                                            </div>
                                            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 3: ALAMAT & KELEMBAGAAN --}}
                                <div class="mb-12">
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="step-num">3</div>
                                        <div>
                                            <h3 class="font-bold" style="color:var(--primary)">ALAMAT & KELEMBAGAAN</h3>
                                            <p class="text-sm" style="color:#94A3B8">Domisili dan keanggotaan poktan</p>
                                        </div>
                                        <div class="flex-1 h-px ml-2" style="background:linear-gradient(to right,#E2E8F0,transparent)"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                        <div class="md:col-span-2">
                                            <label class="label-pro" for="alamat_lengkap">Alamat Lengkap <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-home icon-el" style="top:14px;transform:none"></i>
                                                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3"
                                                    class="textarea-pro {{ $errors->has('alamat_lengkap') ? 'is-invalid' : '' }}"
                                                    style="padding-left:40px"
                                                    placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan...">{{ old('alamat_lengkap') }}</textarea>
                                            </div>
                                            @error('alamat_lengkap')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="nama_poktan">Nama Poktan <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-users icon-el"></i>
                                                <input type="text" name="nama_poktan" id="nama_poktan"
                                                    class="input-pro has-icon {{ $errors->has('nama_poktan') ? 'is-invalid' : '' }}"
                                                    placeholder="Contoh: Poktan Makmur"
                                                    value="{{ old('nama_poktan') }}" />
                                            </div>
                                            @error('nama_poktan')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="alamat_poktan">Alamat Poktan <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-map-marker-alt icon-el"></i>
                                                <input type="text" name="alamat_poktan" id="alamat_poktan"
                                                    class="input-pro has-icon {{ $errors->has('alamat_poktan') ? 'is-invalid' : '' }}"
                                                    placeholder="Alamat kelompok tani"
                                                    value="{{ old('alamat_poktan') }}" />
                                            </div>
                                            @error('alamat_poktan')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="nip">NIP <span class="opt">(opsional)</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-shield-alt icon-el"></i>
                                                <input type="text" name="nip" id="nip"
                                                    class="input-pro has-icon"
                                                    placeholder="Masukkan NIP jika ada"
                                                    value="{{ old('nip') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 4: AKUN LOGIN --}}
                                <div class="mb-12">
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="step-num">4</div>
                                        <div>
                                            <h3 class="font-bold" style="color:var(--primary)">AKUN LOGIN</h3>
                                            <p class="text-sm" style="color:#94A3B8">Buat akun untuk masuk panel peserta</p>
                                        </div>
                                        <div class="flex-1 h-px ml-2" style="background:linear-gradient(to right,#E2E8F0,transparent)"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                                        <div>
                                            <label class="label-pro" for="password">Password <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-lock icon-el"></i>
                                                <input type="password" name="password" id="password"
                                                    class="input-pro has-icon {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                    placeholder="Minimal 6 karakter" />
                                            </div>
                                            @error('password')<span class="error-msg">{{ $message }}</span>@enderror
                                        </div>

                                        <div>
                                            <label class="label-pro" for="password_confirmation">Ulangi Password <span class="req">*</span></label>
                                            <div class="icon-field">
                                                <i class="fas fa-lock icon-el"></i>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                    class="input-pro has-icon"
                                                    placeholder="Ulangi password" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="flex flex-col items-center gap-4 pt-8 border-t" style="border-color:#E2E8F0">
                                    <button type="submit" class="btn-cta">
                                        <i class="fas fa-paper-plane"></i>
                                        KIRIM PENDAFTARAN
                                    </button>
                                    <p class="text-sm text-center max-w-md" style="color:#94A3B8">
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
                    <div class="card-pro anim-in d2">
                        <div class="section-hdr" style="background:linear-gradient(135deg,#7F1D1D,#991B1B)">
                            <div class="icon-box"><i class="fas fa-search"></i></div>
                            <div>
                                <h2 class="text-base font-bold">CEK NIK</h2>
                                <p class="text-red-200/80 text-xs mt-0.5">Periksa data keikutsertaan</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-3">
                                <div class="relative flex-1">
                                    <input type="text" id="cekNikInput" maxlength="16"
                                        class="input-pro has-icon" placeholder="Masukkan NIK" />
                                    <i class="fas fa-id-card icon-el"></i>
                                </div>
                                <button onclick="cekNik()" type="button"
                                    class="btn-outline !text-white !border-red-500 !bg-red-500 hover:!bg-red-600 px-4">
                                    <i class="fas fa-search" id="cekNikIcon"></i>
                                </button>
                            </div>
                            <div id="cekNikResult" class="mt-5 space-y-4"></div>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="card-pro anim-in d3">
                        <div class="p-6">
                            <h3 class="font-bold mb-5 flex items-center gap-2.5" style="color:var(--primary)">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-100">
                                    <i class="fas fa-gift text-emerald-600"></i>
                                </span>
                                Fasilitas Peserta
                            </h3>
                            <div class="space-y-3">
                                @if(!empty($pengaturan->fasilitas))
                                    @foreach($pengaturan->fasilitas as $item)
                                    <div class="flex items-center gap-3 text-sm" style="color:#475569">
                                        <span class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 bg-emerald-50">
                                            <i class="fas fa-check text-emerald-500 text-xs"></i>
                                        </span>
                                        <span>{{ $item['nama'] ?? $item }}</span>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-sm" style="color:#94A3B8">Tidak ada fasilitas tersedia.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="card-pro anim-in d4">
                        <div class="p-6">
                            <h3 class="font-bold mb-5 flex items-center gap-2.5" style="color:var(--primary)">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-100">
                                    <i class="fas fa-map-marker-alt text-red-500"></i>
                                </span>
                                Lokasi Pelatihan
                            </h3>
                            <div class="rounded-xl p-5 border" style="background:#F8FAFC;border-color:#E2E8F0">
                                <p class="font-medium mb-2.5" style="color:var(--primary)">{{ $pengaturan->info ?? '' }}</p>
                                <p class="text-sm flex items-start gap-2.5" style="color:#64748B">
                                    <i class="fas fa-location-dot text-red-400 mt-0.5"></i>
                                    <span>{{ $pengaturan->lokasi ?? '' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer-pro mt-auto" role="contentinfo">
        <div class="container mx-auto px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-3 gap-10 lg:gap-16">
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5">
                        <i class="fas fa-building text-sky-400"></i> Kontak Kami
                    </h3>
                    <div class="space-y-4 text-sm text-slate-300">
                        <p class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-sky-400 mt-0.5"></i>
                            <span>Jl. Raya Magelang-Semarang Km.12,8 Soropadan Pringsurat, Kab. Temanggung</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-phone text-sky-400"></i>
                            <a href="tel:0293123456" class="hover:text-white transition-colors">(0293) 123456</a>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-envelope text-sky-400"></i>
                            <a href="mailto:bapeltan.jateng@pertanian.go.id" class="hover:text-white transition-colors">bapeltan.jateng@pertanian.go.id</a>
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5">
                        <i class="fas fa-link text-sky-400"></i> Tautan
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400"></i> Jadwal Pelatihan</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400"></i> Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400"></i> FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5">
                        <i class="fas fa-share-alt text-sky-400"></i> Ikuti Kami
                    </h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-sky-600 rounded-xl flex items-center justify-center transition-all" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-pink-600 rounded-xl flex items-center justify-center transition-all" aria-label="Instagram">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-red-600 rounded-xl flex items-center justify-center transition-all" aria-label="YouTube">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-700/50 mt-10 pt-8 text-center text-sm text-slate-400">
                <p>&copy; {{ date('Y') }} Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // ── Kabupaten searchable select ───────────────────────────────────────
        const kabItems   = document.querySelectorAll('.kab-item');
        const kabSearch  = document.getElementById('kabupatenSearch');
        const kabHidden  = document.getElementById('kabupaten_id');
        const kabDropdown = document.getElementById('kabDropdown');

        kabSearch.addEventListener('focus', () => kabDropdown.classList.add('open'));
        kabSearch.addEventListener('input', () => {
            const q = kabSearch.value.toLowerCase();
            let found = 0;
            kabItems.forEach(item => {
                const match = item.dataset.nama.toLowerCase().includes(q);
                item.style.display = match ? '' : 'none';
                if (match) found++;
            });
            kabDropdown.classList.toggle('open', found > 0);
            if (!kabSearch.value) kabHidden.value = '';
        });

        kabItems.forEach(item => {
            item.addEventListener('mousedown', e => {
                e.preventDefault();
                kabSearch.value  = item.dataset.nama;
                kabHidden.value  = item.dataset.id;
                kabDropdown.classList.remove('open');
                document.querySelectorAll('.kab-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            });
        });

        document.addEventListener('click', e => {
            if (!kabSearch.contains(e.target) && !kabDropdown.contains(e.target)) {
                kabDropdown.classList.remove('open');
            }
        });

        // ── Cek NIK ──────────────────────────────────────────────────────────
        async function cekNik() {
            const nik  = document.getElementById('cekNikInput').value;
            const icon = document.getElementById('cekNikIcon');
            const result = document.getElementById('cekNikResult');
            if (!nik) return;

            icon.className = 'fas fa-spinner fa-spin';
            result.innerHTML = '';

            try {
                const r = await fetch(`/api/cek-nik?nik=${nik}`);
                const d = await r.json();
                if (d.success) {
                    result.innerHTML = `
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <h3 class="font-bold text-emerald-800 flex items-center gap-2 mb-3">
                                <i class="fas fa-check-circle"></i> NIK Ditemukan
                            </h3>
                            <div class="space-y-1.5 text-sm text-emerald-700">
                                <p><span class="font-semibold">Nama:</span> ${d.data.nama}</p>
                                <p><span class="font-semibold">NIK:</span> ${d.data.nik}</p>
                                <p><span class="font-semibold">Alamat:</span> ${d.data.alamat}</p>
                                <p><span class="font-semibold">Poktan:</span> ${d.data.poktan}</p>
                            </div>
                        </div>`;
                } else {
                    result.innerHTML = `
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-red-700 text-sm font-medium flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> ${d.message}
                            </p>
                        </div>`;
                }
            } catch(e) {
                result.innerHTML = `<div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-700 text-sm">Terjadi kesalahan saat memeriksa NIK.</p></div>`;
            }
            icon.className = 'fas fa-search';
        }
    </script>
</body>
</html>
