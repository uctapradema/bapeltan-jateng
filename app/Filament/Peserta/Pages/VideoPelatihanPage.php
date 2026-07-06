<?php

namespace App\Filament\Peserta\Pages;

use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class VideoPelatihanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Video Pelatihan';
    protected static ?string $navigationGroup = 'Pelatihan';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.peserta.pages.video-pelatihan';

    public ?string $selectedKegiatanId = null;
    public bool $isPlaying = false;

    public function getTitle(): string
    {
        return 'Video Pelatihan';
    }

    public function getKegiatanVideoList(): array
    {
        $user = Auth::user();
        if (!$user->peserta) return [];

        $registrasis = RegistrasiUlang::where('peserta_id', $user->peserta->id)
            ->whereIn('status', ['diterima', 'bersedia', 'selesai'])
            ->with('kegiatan')
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($registrasis as $reg) {
            $kegiatan = $reg->kegiatan;
            if (!$kegiatan || !$kegiatan->video_url) continue;

            $result[] = [
                'kegiatan_id' => $kegiatan->id,
                'kode' => $kegiatan->kode_pelatihan ?? '-',
                'nama' => $kegiatan->nama_pelatihan ?? '-',
                'jenis' => $kegiatan->kegiatanType?->nama_type ?? '-',
                'mulai' => $kegiatan->tanggal_mulai?->format('d M Y') ?? '-',
                'selesai' => $kegiatan->tanggal_selesai?->format('d M Y') ?? '-',
                'video_url' => $kegiatan->video_url,
                'embed_url' => $this->getYouTubeEmbedUrl($kegiatan->video_url),
                'thumbnail_url' => $this->getYouTubeThumbnail($kegiatan->video_url),
                'status_reg' => $reg->status,
            ];
        }

        return $result;
    }

    public function selectKegiatan(string $kegiatanId): void
    {
        $this->selectedKegiatanId = $kegiatanId;
        $this->isPlaying = false;
    }

    public function playVideo(?string $kegiatanId = null): void
    {
        if ($kegiatanId) {
            $this->selectedKegiatanId = $kegiatanId;
        }
        $this->isPlaying = true;
    }

    public function stopPlay(): void
    {
        $this->isPlaying = false;
    }

    public function getSelectedKegiatan(): ?array
    {
        $list = $this->getKegiatanVideoList();
        foreach ($list as $item) {
            if ($item['kegiatan_id'] === $this->selectedKegiatanId) {
                return $item;
            }
        }
        return null;
    }

    public function getYouTubeEmbedUrl(?string $url): ?string
    {
        if (!$url) return null;

        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $url;
        }

        return null;
    }

    public function getYouTubeThumbnail(?string $url): ?string
    {
        if (!$url) return null;

        $videoId = null;

        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        return null;
    }
}
