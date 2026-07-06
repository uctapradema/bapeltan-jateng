<?php

namespace App\Filament\Peserta\Pages;

use App\Models\Materi;
use App\Models\RegistrasiUlang;
use Filament\Pages\Page;

class MateriPelatihanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Pelatihan';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Materi Pelatihan';

    protected static ?string $title = 'Materi Pelatihan';

    protected static string $view = 'filament.peserta.pages.materi-pelatihan';

    public array $kegiatans = [];

    public ?string $selectedKegiatanId = null;

    public bool $isPlaying = false;

    public string $currentEmbedUrl = '';

    public string $currentJudul = '';

    public function mount(): void
    {
        $user = auth()->user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return;
        }

        // Ambil semua kegiatan yang pernah didaftar (termasuk selesai)
        $registrasis = RegistrasiUlang::with(['kegiatan.materis', 'kegiatan.kegiatanType'])
            ->where('peserta_id', $peserta->id)
            ->whereIn('status', ['diterima', 'bersedia', 'selesai'])
            ->get();

        foreach ($registrasis as $reg) {
            $kegiatan = $reg->kegiatan;
            if (!$kegiatan || $kegiatan->materis->isEmpty()) {
                continue;
            }

            $this->kegiatans[] = [
                'id' => $kegiatan->id,
                'kode' => $kegiatan->kode_pelatihan,
                'nama' => $kegiatan->nama_pelatihan,
                'jenis' => $kegiatan->kegiatanType->nama_type ?? '-',
                'tanggal_mulai' => $kegiatan->tanggal_mulai?->format('d M Y') ?? '-',
                'tanggal_selesai' => $kegiatan->tanggal_selesai?->format('d M Y') ?? '-',
                'status_reg' => $reg->status,
                'materis' => $kegiatan->materis->map(fn ($m) => [
                    'id' => $m->id,
                    'judul' => $m->judul,
                    'deskripsi' => $m->deskripsi,
                    'tipe' => $m->tipe,
                    'url' => $m->url,
                    'file_path' => $m->file_path,
                    'file_url' => $m->file_url,
                    'embed_url' => $m->embed_url,
                    'thumbnail_url' => $m->thumbnail_url,
                    'urutan' => $m->urutan,
                ])->toArray(),
            ];
        }
    }

    public function selectKegiatan(string $kegiatanId): void
    {
        $this->selectedKegiatanId = $kegiatanId;
        $this->isPlaying = false;
    }

    public function playVideo(string $embedUrl, string $judul): void
    {
        $this->currentEmbedUrl = $embedUrl;
        $this->currentJudul = $judul;
        $this->isPlaying = true;
    }

    public function stopPlay(): void
    {
        $this->isPlaying = false;
        $this->currentEmbedUrl = '';
        $this->currentJudul = '';
    }

    public function getSelectedKegiatan(): ?array
    {
        foreach ($this->kegiatans as $kegiatan) {
            if ($kegiatan['id'] === $this->selectedKegiatanId) {
                return $kegiatan;
            }
        }
        return null;
    }
}
