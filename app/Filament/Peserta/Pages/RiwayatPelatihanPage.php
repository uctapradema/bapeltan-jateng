<?php

namespace App\Filament\Peserta\Pages;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class RiwayatPelatihanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Pelatihan';
    protected static ?string $navigationGroup = 'Pelatihan';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.peserta.pages.riwayat-pelatihan';

    public array $riwayat = [];

    public function getTitle(): string
    {
        return "Riwayat Pelatihan";
    }

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $registrasis = RegistrasiUlang::with(['kegiatan.kegiatanType', 'kegiatan.tahapans'])
            ->where('peserta_id', $user->peserta->id)
            ->orderByDesc('created_at')
            ->get();

        $this->riwayat = $registrasis->map(function ($reg) {
            $kegiatan = $reg->kegiatan;
            $tahapans = $kegiatan->tahapans()->orderBy('urutan')->get();
            $totalTahapans = $tahapans->count();

            $progress = PelatihanTahapanProgress::where('peserta_id', $reg->peserta_id)
                ->whereIn('tahapan_id', $tahapans->pluck('id'))
                ->get();

            $completedTahapans = $progress->where('status', 'completed')->count();
            $persentase = $totalTahapans > 0 ? round(($completedTahapans / $totalTahapans) * 100) : 0;

            return [
                'id' => $reg->id,
                'kegiatan' => $kegiatan,
                'status' => $reg->status,
                'tanggal_daftar' => $reg->created_at,
                'tanggal_selesai' => $reg->tanggal_selesai_pelatihan,
                'total_tahapans' => $totalTahapans,
                'completed_tahapans' => $completedTahapans,
                'persentase' => $persentase,
                'tipe_kegiatan' => $kegiatan->kegiatanType?->nama_type ?? '-',
            ];
        })->toArray();
    }
}
