<?php

namespace App\Filament\Peserta\Pages;

use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SertifikatPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Sertifikat';
    protected static ?string $navigationGroup = 'Pelatihan';
    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.peserta.pages.sertifikat';

    public array $sertifikats = [];

    public function getTitle(): string
    {
        return 'Sertifikat Pelatihan';
    }

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $this->sertifikats = RegistrasiUlang::with(['kegiatan.kegiatanType'])
            ->where('peserta_id', $user->peserta->id)
            ->where('status', 'selesai')
            ->latest()
            ->get()
            ->map(fn ($reg) => [
                'id' => $reg->id,
                'nama' => $reg->kegiatan->nama_pelatihan ?? '-',
                'kode' => $reg->kegiatan->kode_pelatihan ?? '-',
                'jenis' => $reg->kegiatan->kegiatanType->nama_type ?? '-',
                'tanggal_selesai' => $reg->tanggal_selesai_pelatihan?->format('d M Y') ?? '-',
                'nomor' => $reg->nomor_sertifikat ?? '-',
                'path' => $reg->sertifikat_path,
                'has_sertifikat' => filled($reg->sertifikat_path),
                'download_url' => route('sertifikat.download', $reg->id),
                'preview_url' => route('sertifikat.preview', $reg->id),
            ])
            ->toArray();
    }
}
