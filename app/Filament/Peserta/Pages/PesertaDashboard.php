<?php

namespace App\Filament\Peserta\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PesertaDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.peserta.pages.peserta-dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    public function getTitle(): string
    {
        $user = Auth::user();
        $nama = $user->peserta->nama ?? $user->name;

        return "Hai, {$nama}!";
    }

    public function getSubtitle(): string
    {
        return 'SELAMAT DATANG DI PELATIHAN MEKANISASI, MODERNISASI DAN DIGITALISASI KOMDITAS TEMBAKAU ANGKATAN I';
    }

    public function getParagraf(): string
    {
        return 'Kompetensi yang diharapkan dalam Pelatihan Mekanisasi, Modernisasi, dan Digitalisasi Komoditas Tembakau adalah kemampuan peserta dalam menguasai pengetahuan, keterampilan, dan sikap profesional untuk menerapkan prinsip-prinsip mekanisasi, modernisasi, dan digitalisasi dalam sistem agribisnis tembakau secara efektif dan berkelanjutan.';
    }
}
