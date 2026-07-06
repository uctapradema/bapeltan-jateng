<?php

namespace App\Filament\Peserta\Pages;

use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class RegistrasiUlangPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Registrasi Ulang';

    protected static string $view = 'filament.peserta.pages.registrasi-ulang';

    public function getTitle(): string
    {
        return 'Registrasi Ulang';
    }

    public function getKegiatanDiikuti(): array
    {
        $user = Auth::user();
        if (!$user->peserta) return [];

        return $user->peserta->registrasiUlangs()
            ->with('kegiatan')
            ->whereIn('status', ['diterima', 'bersedia'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($reg) => [
                'id' => $reg->id,
                'kegiatan_id' => $reg->kegiatan_id,
                'nama' => $reg->kegiatan->nama_pelatihan ?? '-',
                'kode' => $reg->kegiatan->kode_pelatihan ?? '-',
                'mulai' => $reg->kegiatan->tanggal_mulai?->format('d M Y') ?? '-',
                'selesai' => $reg->kegiatan->tanggal_selesai?->format('d M Y') ?? '-',
                'status' => $reg->status,
            ])
            ->toArray();
    }

    public function bersediaKegiatan(string $registrasiId): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $registrasi = RegistrasiUlang::with('kegiatan')
            ->where('id', $registrasiId)
            ->where('peserta_id', $user->peserta->id)
            ->where('status', 'diterima')
            ->first();

        if (!$registrasi) {
            session()->flash('danger', 'Registrasi tidak ditemukan.');
            return;
        }

        $registrasi->update(['status' => 'bersedia']);

        $namaKegiatan = $registrasi->kegiatan?->nama_pelatihan ?? '-';
        session()->flash('success', "Konfirmasi kebersediaan untuk \"{$namaKegiatan}\" berhasil!");
    }
}
