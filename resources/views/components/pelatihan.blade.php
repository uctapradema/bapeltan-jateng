<div class="relative">
    <!-- Container luar -->
    <div class="container mx-auto px-10 lg:px-50">
        <!-- Card mengambang -->
        <div
            class="bg-white rounded shadow-[0_6px_12px_rgba(0,0,0,0.12),_-6px_0_12px_rgba(0,0,0,0.06),_6px_0_12px_rgba(0,0,0,0.06)] p-6 md:p-10 -mt-24 relative z-10">

            <!-- HEADER -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">
                    Materi Pelatihan
                </h1>

                <div class="hidden sm:flex items-center gap-3">
                    <a href="#" class="btn btn-success rounded">
                        Lihat Semua
                    </a>
                </div>
            </div>

            <!-- GRID CARD -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="card bg-base-100">
                        <figure>
                            <img src="https://picsum.photos/400/300?random={{ $i }}" alt="Pelatihan" />
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title">Judul Pelatihan</h2>
                            <p class="text-sm opacity-80">
                                Deskripsi singkat materi pelatihan
                            </p>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- ACTION MOBILE -->
            <div class="flex justify-center mt-8 sm:hidden">
                <div class="flex items-center gap-2">
                    <a href="#" class="btn btn btn-success rounded">
                        Lihat Semua
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
