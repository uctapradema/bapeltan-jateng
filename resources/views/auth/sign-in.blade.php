<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balai Pelatihan Pertanian Jawa Tengah</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-fixed bg-cover bg-center">

    <!-- Desktop Navbar -->
    
    <x-navbar />

    <!-- Konten -->
    <main class="space-y-12">
        <div class="bg-success">
            <x-welcome />
        </div>
        <x-pelatihan />
        <x-artikel />
        <x-video />
        <x-partner />
    </main>

    <div class="bg-success mt-4">
        <x-top-footer />
    </div>
    <div class="bg-neutral pb-[3.5rem] md:pb-0">
        <x-bottom-footer />
    </div>

    <!-- ================= DOCK MOBILE ================= -->
    <div
        class="dock dock-sm fixed bottom-0 left-0 right-0 z-50 bg-base-200 shadow-[0_-4px_10px_rgba(0,0,0,0.15)] md:hidden">

        <!-- Home -->
        <button onclick="window.location='/'" class="{{ request()->is('/') ? 'dock-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-[1.2em]">
                <path
                    d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z" />
            </svg>
        </button>

        <!-- Artikel -->
        {{-- <button onclick="window.location='{{ route('artikel.index') }}'" --}}
        <button onclick="window.location='/'" class="{{ request()->is('artikel*') ? 'dock-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-[1.2em]">
                <path
                    d="M64 480L64 184C64 170.7 74.7 160 88 160C101.3 160 112 170.7 112 184L112 472C112 485.3 122.7 496 136 496C149.3 496 160 485.3 160 472L160 160C160 124.7 188.7 96 224 96L512 96C547.3 96 576 124.7 576 160L576 480C576 515.3 547.3 544 512 544L128 544C92.7 544 64 515.3 64 480zM224 192L224 256C224 273.7 238.3 288 256 288L320 288C337.7 288 352 273.7 352 256L352 192C352 174.3 337.7 160 320 160L256 160C238.3 160 224 174.3 224 192zM248 432C234.7 432 224 442.7 224 456C224 469.3 234.7 480 248 480L488 480C501.3 480 512 469.3 512 456C512 442.7 501.3 432 488 432L248 432zM224 360C224 373.3 234.7 384 248 384L488 384C501.3 384 512 373.3 512 360C512 346.7 501.3 336 488 336L248 336C234.7 336 224 346.7 224 360zM424 240C410.7 240 400 250.7 400 264C400 277.3 410.7 288 424 288L488 288C501.3 288 512 277.3 512 264C512 250.7 501.3 240 488 240L424 240z" />
            </svg>
        </button>

        <!-- Pelatihan -->
        {{-- <button onclick="window.location='{{ route('pelatihan.index') }}'" --}}
        <button onclick="window.location='/'" class="{{ request()->is('pelatihan*') ? 'dock-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-[1.2em]">
                <path
                    d="M320 205.3L320 514.6L320.5 514.4C375.1 491.7 433.7 480 492.8 480L512 480L512 160L492.8 160C450.6 160 408.7 168.4 369.7 184.6C352.9 191.6 336.3 198.5 320 205.3zM294.9 125.5L320 136L345.1 125.5C391.9 106 442.1 96 492.8 96L528 96C554.5 96 576 117.5 576 144L576 496C576 522.5 554.5 544 528 544L492.8 544C442.1 544 391.9 554 345.1 573.5L332.3 578.8C324.4 582.1 315.6 582.1 307.7 578.8L294.9 573.5C248.1 554 197.9 544 147.2 544L112 544C85.5 544 64 522.5 64 496L64 144C64 117.5 85.5 96 112 96L147.2 96C197.9 96 248.1 106 294.9 125.5z" />
            </svg>
        </button>

        <!-- Informasi -->
        {{-- <button onclick="window.location='{{ route('informasi.index') }}'" --}}
        <button onclick="window.location='/'" class="{{ request()->is('pelatihan*') ? 'dock-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-[1.2em]">
                <path
                    d="M320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 461.4 178.6 576 320 576zM288 224C288 206.3 302.3 192 320 192C337.7 192 352 206.3 352 224C352 241.7 337.7 256 320 256C302.3 256 288 241.7 288 224zM280 288L328 288C341.3 288 352 298.7 352 312L352 400L360 400C373.3 400 384 410.7 384 424C384 437.3 373.3 448 360 448L280 448C266.7 448 256 437.3 256 424C256 410.7 266.7 400 280 400L304 400L304 336L280 336C266.7 336 256 325.3 256 312C256 298.7 266.7 288 280 288z" />
            </svg>
        </button>

        <!-- Kontak -->
        <button onclick="window.location='tel:+628123456789'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="size-[1.2em]">
                <path
                    d="M160 64C124.7 64 96 92.7 96 128L96 512C96 547.3 124.7 576 160 576L448 576C483.3 576 512 547.3 512 512L512 128C512 92.7 483.3 64 448 64L160 64zM272 352L336 352C380.2 352 416 387.8 416 432C416 440.8 408.8 448 400 448L208 448C199.2 448 192 440.8 192 432C192 387.8 227.8 352 272 352zM248 256C248 225.1 273.1 200 304 200C334.9 200 360 225.1 360 256C360 286.9 334.9 312 304 312C273.1 312 248 286.9 248 256zM576 144C576 135.2 568.8 128 560 128C551.2 128 544 135.2 544 144L544 208C544 216.8 551.2 224 560 224C568.8 224 576 216.8 576 208L576 144zM576 272C576 263.2 568.8 256 560 256C551.2 256 544 263.2 544 272L544 336C544 344.8 551.2 352 560 352C568.8 352 576 344.8 576 336L576 272zM560 384C551.2 384 544 391.2 544 400L544 464C544 472.8 551.2 480 560 480C568.8 480 576 472.8 576 464L576 400C576 391.2 568.8 384 560 384z" />
            </svg>
        </button>
    </div>
    <!-- ================= END DOCK ================= -->

    <!-- Modal Login -->
    <dialog id="loginModal" class="modal">
        <div class="modal-box relative max-w-md w-auto p-6 mx-4 sm:mx-auto">
            <button type="button" class="absolute right-2 top-2" onclick="loginModal.close()">✕</button>

            <h2 class="text-center text-xl font-bold mb-4">Silahkan Masuk</h2>

            @if($errors->any())
                <div class="alert alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('name_or_email', 'Username atau password salah.') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.perform') }}" class="space-y-4">
                @csrf

                <input type="text" name="name_or_email" required class="input input-bordered w-full @error('name_or_email') input-error @enderror"
                    placeholder="Nama / Email" value="{{ old('name_or_email') }}">

                <input type="password" name="password" required class="input input-bordered w-full"
                    placeholder="Password">

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="checkbox checkbox-success">
                    Remember me
                </label>

                <button class="btn btn-success w-full text-base-200">
                    Masuk
                </button>
            </form>

            <div class="divider">atau</div>

            <a href="{{ route('public.registration') }}" class="btn btn-neutral w-full">
                Pendaftaran
            </a>
        </div>
    </dialog>

    <script>
        const loginModal = document.getElementById('loginModal');
        @if($errors->any())
            loginModal.showModal();
        @endif
    </script>

</body>

</html>
