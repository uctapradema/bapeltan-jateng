<!DOCTYPE html>
<html lang="id" data-theme="light" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Pelatihan - Bapeltan Jateng</title>

    @vite('resources/css/app.css')
    @livewireStyles

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

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Lexend', sans-serif;
        }

        /* Design Tokens - Government Accessible & Ethical */
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
            --warning: #D97706;
            --radius: 12px;
        }

        /* Hero - Government Gradient */
        .hero-bg {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F4C75 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(3,105,161,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(3,105,161,0.1) 0%, transparent 50%);
        }

        /* Cards - Clean Government Style */
        .card-pro {
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s ease;
        }
        .card-pro:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Section Header - Navy Blue */
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
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Step Badge */
        .step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            font-size: 15px;
            font-weight: 700;
            flex-shrink: 0;
            font-family: 'Lexend', sans-serif;
        }

        /* Inputs - Accessible (44px touch target) */
        .input-pro {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #FFFFFF;
            font-size: 16px;
            color: var(--foreground);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: 'Source Sans 3', sans-serif;
        }
        .input-pro:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(3,105,161,0.15);
        }
        .input-pro::placeholder { color: #94A3B8; }
        .input-pro.has-icon { padding-left: 44px; }

        .select-pro {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #FFFFFF;
            font-size: 16px;
            color: var(--foreground);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 44px;
            font-family: 'Source Sans 3', sans-serif;
        }
        .select-pro:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(3,105,161,0.15);
        }

        .textarea-pro {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #FFFFFF;
            font-size: 16px;
            color: var(--foreground);
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: 'Source Sans 3', sans-serif;
            line-height: 1.6;
        }
        .textarea-pro:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(3,105,161,0.15);
        }

        /* Labels */
        .label-pro {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: 600;
            color: var(--secondary);
            line-height: 1.5;
        }
        .label-pro .req { color: var(--destructive); margin-left: 2px; }
        .label-pro .opt { color: #94A3B8; font-weight: 400; font-size: 14px; margin-left: 4px; }
        .error-msg {
            display: block;
            margin-top: 6px;
            font-size: 14px;
            color: var(--destructive);
        }

        /* Icon wrapper */
        .icon-field {
            position: relative;
        }
        .icon-field .icon-el {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 15px;
            pointer-events: none;
        }

        /* CTA Button - High Contrast */
        .btn-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 52px;
            padding: 0 48px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Lexend', sans-serif;
            letter-spacing: 0.02em;
        }
        .btn-cta:hover {
            background: var(--accent-hover);
            box-shadow: 0 4px 16px rgba(3,105,161,0.3);
        }
        .btn-cta:active { transform: translateY(0); }
        .btn-cta:focus-visible {
            outline: 3px solid var(--ring);
            outline-offset: 2px;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 44px;
            padding: 0 20px;
            background: transparent;
            color: var(--accent);
            border: 1.5px solid var(--accent);
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-outline:hover {
            background: var(--accent);
            color: white;
        }
        .btn-outline:focus-visible {
            outline: 3px solid var(--ring);
            outline-offset: 2px;
        }

        /* Footer */
        .footer-pro {
            background: var(--primary);
            color: white;
        }

        /* Focus visible for accessibility */
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid var(--ring);
            outline-offset: 2px;
        }

        /* Skip link (accessibility) */
        .skip-link {
            position: absolute;
            top: -100%;
            left: 16px;
            background: var(--accent);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            z-index: 100;
            font-weight: 600;
            text-decoration: none;
        }
        .skip-link:focus {
            top: 16px;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <!-- Header -->
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
                    <h1 class="text-xl lg:text-2xl font-bold tracking-tight leading-tight" style="font-family: 'Lexend', sans-serif">BALAI PELATIHAN PERTANIAN</h1>
                    <p class="text-sky-200 text-sm lg:text-base font-medium mt-0.5">Provinsi Jawa Tengah</p>
                    <a href="/" class="inline-flex items-center gap-2 mt-3 text-xs lg:text-sm text-sky-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        Kembali ke Beranda
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

    <!-- Main -->
    <main id="main-content" class="flex-1 container mx-auto px-6 lg:px-8 py-10 lg:py-14" role="main">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer-pro mt-auto" role="contentinfo">
        <div class="container mx-auto px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-3 gap-10 lg:gap-16">
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5" style="font-family: 'Lexend', sans-serif">
                        <i class="fas fa-building text-sky-400"></i>
                        Kontak Kami
                    </h3>
                    <div class="space-y-4 text-sm text-slate-300">
                        <p class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-sky-400 mt-0.5" aria-hidden="true"></i>
                            <span>Jl. Raya Magelang-Semarang Km.12,8 Soropadan Pringsurat, Kab. Temanggung</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-phone text-sky-400" aria-hidden="true"></i>
                            <a href="tel:0293123456" class="hover:text-white transition-colors">(0293) 123456</a>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-envelope text-sky-400" aria-hidden="true"></i>
                            <a href="mailto:bapeltan.jateng@pertanian.go.id" class="hover:text-white transition-colors">bapeltan.jateng@pertanian.go.id</a>
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5" style="font-family: 'Lexend', sans-serif">
                        <i class="fas fa-link text-sky-400" aria-hidden="true"></i>
                        Tautan
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400" aria-hidden="true"></i> Jadwal Pelatihan</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400" aria-hidden="true"></i> Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-sky-400" aria-hidden="true"></i> FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-5 flex items-center gap-2.5" style="font-family: 'Lexend', sans-serif">
                        <i class="fas fa-share-alt text-sky-400" aria-hidden="true"></i>
                        Ikuti Kami
                    </h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-sky-600 rounded-xl flex items-center justify-center transition-all duration-200" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-lg" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-pink-600 rounded-xl flex items-center justify-center transition-all duration-200" aria-label="Instagram">
                            <i class="fab fa-instagram text-lg" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/10 hover:bg-red-600 rounded-xl flex items-center justify-center transition-all duration-200" aria-label="YouTube">
                            <i class="fab fa-youtube text-lg" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-700/50 mt-10 pt-8 text-center text-sm text-slate-400">
                <p>&copy; {{ date('Y') }} Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
