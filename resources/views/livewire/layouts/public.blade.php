<!DOCTYPE html>
<html lang="id" data-theme="light" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Pelatihan - Bapeltan Jateng</title>

    @vite('resources/css/app.css')
    @livewireStyles

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Hero Gradient */
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-gradient::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Card Styles */
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
            transition: box-shadow 0.3s ease;
        }
        .form-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.08);
        }

        /* Section Header */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            margin: 0;
        }
        .section-header .icon-box {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Step Indicator */
        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Input Focus */
        .input, .select, .textarea {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input:focus, .select:focus, .textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            padding: 14px 48px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.45);
        }
        .btn-submit:active {
            transform: translateY(0);
        }

        /* Footer */
        .footer-gradient {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="hero-gradient text-white relative z-10">
        <div class="container mx-auto px-4 py-6 lg:py-8">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <a href="/" class="flex-shrink-0">
                        <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center p-2 border border-white/20">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg/600px-Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg.png"
                                alt="Logo" class="w-full h-full object-contain">
                        </div>
                    </a>
                </div>
                <div class="text-right">
                    <h1 class="text-xl lg:text-2xl font-bold tracking-tight">BALAI PELATIHAN PERTANIAN</h1>
                    <p class="text-blue-200 text-sm lg:text-base font-medium">Provinsi Jawa Tengah</p>
                    <a href="/" class="inline-flex items-center gap-1.5 mt-2 text-xs lg:text-sm text-blue-100 hover:text-white transition-colors bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        <!-- Wave Bottom -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 60L48 52C96 44 192 28 288 22C384 16 480 20 576 26C672 32 768 40 864 42C960 44 1056 40 1152 34C1248 28 1344 20 1392 16L1440 12V60H1392C1344 60 1248 60 1152 60C1056 60 960 60 864 60C768 60 672 60 576 60C480 60 384 60 288 60C192 60 96 60 48 60H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-8 lg:py-12">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer-gradient text-white mt-auto">
        <div class="container mx-auto px-4 py-10">
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div>
                    <h3 class="text-base font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-building text-blue-400"></i>
                        Kontak Kami
                    </h3>
                    <div class="space-y-3 text-sm text-gray-300">
                        <p class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-0.5"></i>
                            <span>Jl. Raya Magelang-Semarang Km.12,8 Soropadan Pringsurat, Kab. Temanggung</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-phone text-blue-400"></i>
                            (0293) 123456
                        </p>
                        <p class="flex items-center gap-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            bapeltan.jateng@pertanian.go.id
                        </p>
                    </div>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-link text-blue-400"></i>
                        Tautan
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-400"></i> Jadwal Pelatihan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-400"></i> Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-400"></i> FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-base font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-share-alt text-blue-400"></i>
                        Ikuti Kami
                    </h3>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-white/10 hover:bg-blue-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 hover:bg-pink-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 hover:bg-red-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700/50 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
