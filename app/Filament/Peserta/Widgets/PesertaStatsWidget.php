<?php

namespace App\Filament\Peserta\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PesertaStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return [
                Stat::make('Status', 'Belum Terdaftar')
                    ->description('Silakan lengkapi biodata')
                    ->descriptionIcon('heroicon-m-information-circle')
                    ->color('warning'),
            ];
        }

        $total = $peserta->registrasiUlangs()->count();
        $diterima = $peserta->registrasiUlangs()->where('status', 'diterima')->count();
        $pending = $peserta->registrasiUlangs()->where('status', 'pending')->count();
        $selesai = $peserta->registrasiUlangs()->where('status', 'selesai')->count();

        return [
            Stat::make('Total Pendaftaran', $total)
                ->description('Semua pelatihan')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('primary'),

            Stat::make('Diterima', $diterima)
                ->description('Pelatihan aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending', $pending)
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Selesai', $selesai)
                ->description('Telah selesai')
                ->descriptionIcon('heroicon-m-flag')
                ->color('info'),
        ];
    }
}
