<?php

namespace App\Filament\Peserta\Pages;

use App\Models\Pengaturan;
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

    public function getPengaturan(): ?Pengaturan
    {
        return Pengaturan::first();
    }

    public function getJumlahPelatihan(): int
    {
        $user = Auth::user();
        if (!$user->peserta) return 0;

        return $user->peserta->registrasiUlangs()->count();
    }

    public function getPelatihanDiterima(): int
    {
        $user = Auth::user();
        if (!$user->peserta) return 0;

        return $user->peserta->registrasiUlangs()->where('status', 'diterima')->count();
    }

    public function getPelatihanSelesai(): int
    {
        $user = Auth::user();
        if (!$user->peserta) return 0;

        return $user->peserta->registrasiUlangs()->where('status', 'selesai')->count();
    }

    public function getPelatihanPending(): int
    {
        $user = Auth::user();
        if (!$user->peserta) return 0;

        return $user->peserta->registrasiUlangs()->where('status', 'pending')->count();
    }

    public function getDaftarPelatihan(): array
    {
        $user = Auth::user();
        if (!$user->peserta) return [];

        return $user->peserta->registrasiUlangs()
            ->with('kegiatan')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($reg) => [
                'nama' => $reg->kegiatan->nama_pelatihan ?? '-',
                'kode' => $reg->kegiatan->kode_pelatihan ?? '-',
                'mulai' => $reg->kegiatan->tanggal_mulai?->format('d M Y') ?? '-',
                'selesai' => $reg->kegiatan->tanggal_selesai?->format('d M Y') ?? '-',
                'status' => $reg->status,
            ])
            ->toArray();
    }
}
