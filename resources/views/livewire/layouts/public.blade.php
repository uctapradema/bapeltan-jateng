<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Pelatihan - Bapeltan Jateng</title>

    @vite('resources/css/app.css')
    @livewireStyles

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .form-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .form-title {
            background: #1e40af;
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            margin: 0;
            font-weight: bold;
        }

        .form-content {
            padding: 1.5rem;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #7f1d1d;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <a href="/">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg/600px-Logo_of_Ministry_of_Agriculture_of_the_Republic_of_Indonesia.svg.png"
                                alt="Logo Pertanian" class="w-14 object-contain">
                        </a>
                    </div>                    
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-bold">BALAI PELATIHAN PERTANIAN</h1>
                    <p class="text-blue-200">Jawa Tengah</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Kontak Kami</h3>
                    <p class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
                        Jl. Raya Magelang-Semarang Km.12,8 Soropadan Pringsurat<br>Kab. Temanggung
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="fas fa-phone mr-3 text-blue-400"></i>
                        (0293) 123456
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-blue-400"></i>
                        bapeltan.jateng@pertanian.go.id
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Jadwal Pelatihan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-blue-400 hover:text-blue-300">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-300">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-300">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; 2024 Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>

</html>
