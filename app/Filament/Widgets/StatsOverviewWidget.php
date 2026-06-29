<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalPeserta = Peserta::count();
        $totalKegiatan = Kegiatan::where('status', 'active')->count();
        $registrasiPending = RegistrasiUlang::where('status', 'pending')->count();
        $registrasiDiterima = RegistrasiUlang::where('status', 'diterima')->count();
        $registrasiSelesai = RegistrasiUlang::where('status', 'selesai')->count();

        $kuotaTerpakai = RegistrasiUlang::where('status', 'diterima')->count();
        $kuotaTotal = Kegiatan::where('status', 'active')->sum('kuota');
        $persentaseKuota = $kuotaTotal > 0 ? round(($kuotaTerpakai / $kuotaTotal) * 100, 1) : 0;

        return [
            Stat::make('Total Peserta', $totalPeserta)
                ->description('Terdaftar dalam sistem')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('Pelatihan Aktif', $totalKegiatan)
                ->description('Sedang berlangsung')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Registrasi Pending', $registrasiPending)
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Diterima', $registrasiDiterima)
                ->description('Peserta diterima')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Selesai', $registrasiSelesai)
                ->description('Pelatihan selesai')
                ->descriptionIcon('heroicon-m-flag')
                ->color('info'),

            Stat::make('Penggunaan Kuota', $persentaseKuota . '%')
                ->description($kuotaTerpakai . ' / ' . $kuotaTotal . ' kuota terpakai')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($persentaseKuota > 80 ? 'danger' : 'primary'),
        ];
    }
}
