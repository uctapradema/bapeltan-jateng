<?php

namespace App\Filament\Peserta\Resources\EvaluasiResource\Pages;

use App\Filament\Peserta\Resources\EvaluasiResource;
use App\Models\Evaluasi;
use App\Models\RegistrasiUlang;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListEvaluasis extends ListRecords
{
    protected static string $resource = EvaluasiResource::class;

    public function getTitle(): string
    {
        return 'Daftar Evaluasi';
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        // Jika bukan peserta → kosongkan table
        if (!$user || !$user->peserta) {
            return Evaluasi::query()->whereRaw('0=1');
        }

        $pesertaNik = $user->peserta->nik;

        // Ambil daftar kegiatan yang statusnya diterima
        $kegiatanIds = RegistrasiUlang::where('peserta_nik', $pesertaNik)
            ->where('status', 'diterima')
            ->pluck('kegiatan_id');

        // Jika peserta belum diterima di kegiatan apapun → kosongkan tabel
        if ($kegiatanIds->isEmpty()) {
            return Evaluasi::query()->whereRaw('0=1');
        }

        // Ambil evaluasi hanya untuk kegiatan yang diterima
        return Evaluasi::query()
            ->whereIn('kegiatan_id', $kegiatanIds)
            ->with(['kegiatan', 'type']);
    }
}
