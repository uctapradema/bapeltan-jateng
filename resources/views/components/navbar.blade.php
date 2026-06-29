<nav class="bg-success sticky top-0 z-50">
    <div class="container mx-auto flex items-center justify-between py-5 px-10 lg:px-50">
        <!-- kiri / logo -->
        <div class="navbar-start flex items-center gap-10">
            <!-- Logo -->
            <img src="{{ asset('img/BapeltanJatengLogo.png') }}" class="h-10" alt="Bapeltan Jateng">
        
            <!-- Menu -->
            <ul class="hidden lg:flex items-center gap-8 font-medium text-base-200">
                <li><x-navbar-hover href="/">Beranda</x-navbar-hover></li>
                <li><x-navbar-hover href="/pelatihan">Pelatihan</x-navbar-hover></li>
                <li><x-navbar-hover href="/artikel">Media</x-navbar-hover></li>
                <li><x-navbar-hover href="/informasi">Informasi</x-navbar-hover></li>
                <li><x-navbar-hover href="/kontak">Kontak</x-navbar-hover></li>
            </ul>
        </div>        

        <!-- kanan -->
        <div class="navbar-end hidden lg:flex w-full max-w-3xl items-center gap-3">
            <!-- tombol login buka modal -->
            <button class="font-semibold btn btn-warning rounded text-base-content"
                onclick="loginModal.showModal()">
                Masuk
            </button>

            <a href="{{ route('public.registration') }}" class="btn text-success btn-base-200 rounded hover:text-base-content">
                Pendaftaran
            </a>
        </div>
    </div>
</nav>
