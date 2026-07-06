<?php

namespace App\Filament\Peserta\Pages;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TahapanListPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Pelatihan Tahapan';

    protected static string $view = 'filament.peserta.pages.tahapan-list';

    public function getTitle(): string
    {
        return 'Pelatihan Tahapan';
    }

    public function getKegiatanList(): array
    {
        $user = Auth::user();
        if (!$user->peserta) return [];

        $registrasis = RegistrasiUlang::where('peserta_id', $user->peserta->id)
            ->whereIn('status', ['diterima', 'bersedia'])
            ->with(['kegiatan.tahapans'])
            ->orderBy('created_at', 'desc')
            ->get();

        $progressCounts = PelatihanTahapanProgress::where('peserta_id', $user->peserta->id)
            ->where('status', 'completed')
            ->with('tahapan')
            ->get()
            ->groupBy(fn ($p) => $p->tahapan?->kegiatan_id ?? null);

        $result = [];
        foreach ($registrasis as $reg) {
            $kegiatan = $reg->kegiatan;
            if (!$kegiatan) continue;

            $totalTahapans = $kegiatan->tahapans->count();
            $completedTahapans = $progressCounts->get($kegiatan->id, collect())->count();

            $persentase = $totalTahapans > 0 ? round(($completedTahapans / $totalTahapans) * 100) : 0;

            $result[] = [
                'kegiatan_id' => $kegiatan->id,
                'kode' => $kegiatan->kode_pelatihan ?? '-',
                'nama' => $kegiatan->nama_pelatihan ?? '-',
                'mulai' => $kegiatan->tanggal_mulai?->format('d M Y') ?? '-',
                'selesai' => $kegiatan->tanggal_selesai?->format('d M Y') ?? '-',
                'total' => $totalTahapans,
                'completed' => $completedTahapans,
                'persentase' => $persentase,
                'status_reg' => $reg->status,
            ];
        }

        return $result;
    }
}
