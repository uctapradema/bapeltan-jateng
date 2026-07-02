<?php

namespace App\Filament\Pages;

use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\RegistrasiUlang;
use App\Models\Kabupaten;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class LaporanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.laporan';

    public ?string $tipe = null;

    public function getTitle(): string
    {
        return "Laporan";
    }

    public function getStatistik(): array
    {
        return [
            'total_peserta' => Peserta::count(),
            'total_kegiatan' => Kegiatan::count(),
            'total_registrasi' => RegistrasiUlang::count(),
            'registrasi_selesai' => RegistrasiUlang::where('status', 'selesai')->count(),
        ];
    }

    public function getPesertaPerKabupaten(): array
    {
        return Peserta::select('kabupaten_id', DB::raw('count(*) as total'))
            ->with('kabupaten')
            ->groupBy('kabupaten_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'nama' => $item->kabupaten->nama ?? '-',
                'total' => $item->total,
            ])
            ->toArray();
    }

    public function getPesertaPerKegiatanType(): array
    {
        return RegistrasiUlang::join('kegiatans', 'registrasi_ulangs.kegiatan_id', '=', 'kegiatans.id')
            ->join('kegiatan_types', 'kegiatans.kegiatan_type_id', '=', 'kegiatan_types.id')
            ->select('kegiatan_types.nama_type', DB::raw('count(*) as total'))
            ->groupBy('kegiatan_types.nama_type')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    public function getRegistrasiPerStatus(): array
    {
        return RegistrasiUlang::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    public function getKegiatanTerbaru(): array
    {
        return Kegiatan::with('kegiatanType')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
