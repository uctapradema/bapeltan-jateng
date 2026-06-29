<?php

namespace App\Filament\Peserta\Pages;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\RegistrasiUlang;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class KegiatanTahapanPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Pelatihan';
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.peserta.pages.kegiatan-tahapan';

    public ?string $kegiatanId = null;
    public ?array $kegiatan = null;
    public ?array $tahapans = [];
    public ?int $totalTahapans = 0;
    public ?int $completedTahapans = 0;
    public ?int $persentase = 0;

    protected static string $routePath = 'kegiatan-tahapan';

    public static function getRoutePath(): string
    {
        return static::$routePath . '/{kegiatanId}';
    }

    public function getRouteKeyName(): ?string
    {
        return 'kegiatanId';
    }

    public function mount(string $kegiatanId): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $this->kegiatanId = $kegiatanId;

        $reg = RegistrasiUlang::where('peserta_nik', $user->peserta->nik)
            ->where('kegiatan_id', $kegiatanId)
            ->where('status', 'diterima')
            ->with('kegiatan')
            ->first();

        if (!$reg) {
            session()->flash('danger', 'Anda tidak terdaftar di kegiatan ini.');
            $this->redirect(route('filament.peserta.pages.peserta-dashboard'));
            return;
        }

        $keg = $reg->kegiatan;
        $this->kegiatan = [
            'id' => $keg->id,
            'nama' => $keg->nama_pelatihan,
            'kode' => $keg->kode_pelatihan,
            'mulai' => $keg->tanggal_mulai?->format('d M Y') ?? '-',
            'selesai' => $keg->tanggal_selesai?->format('d M Y') ?? '-',
        ];

        $allTahapans = PelatihanTahapan::where('kegiatan_id', $kegiatanId)
            ->orderBy('urutan')
            ->get();

        $progressMap = PelatihanTahapanProgress::where('peserta_nik', $user->peserta->nik)
            ->whereIn('tahapan_id', $allTahapans->pluck('id'))
            ->pluck('status', 'tahapan_id')
            ->toArray();

        $this->tahapans = $allTahapans->map(function ($t) use ($progressMap) {
            $status = $progressMap[$t->id] ?? 'locked';
            return [
                'id' => $t->id,
                'nama' => $t->nama,
                'deskripsi' => $t->deskripsi,
                'urutan' => $t->urutan,
                'tipe' => $t->tipe,
                'link' => $t->link,
                'wajib' => $t->wajib,
                'status' => $status,
            ];
        })->toArray();

        $this->totalTahapans = count($this->tahapans);
        $this->completedTahapans = collect($this->tahapans)->where('status', 'completed')->count();
        $this->persentase = $this->totalTahapans > 0
            ? round(($this->completedTahapans / $this->totalTahapans) * 100)
            : 0;
    }

    public function selesaikanTahapan(string $tahapanId): void
    {
        $user = Auth::user();
        $peserta = $user->peserta;
        if (!$peserta) return;

        $tahapan = PelatihanTahapan::find($tahapanId);
        if (!$tahapan || $tahapan->kegiatan_id !== $this->kegiatanId) return;

        PelatihanTahapanProgress::updateOrCreate(
            ['tahapan_id' => $tahapanId, 'peserta_nik' => $peserta->nik],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        $next = PelatihanTahapan::where('kegiatan_id', $tahapan->kegiatan_id)
            ->where('urutan', '>', $tahapan->urutan)
            ->orderBy('urutan')
            ->first();

        if ($next) {
            PelatihanTahapanProgress::updateOrCreate(
                ['tahapan_id' => $next->id, 'peserta_nik' => $peserta->nik],
                ['status' => 'active']
            );
        }

        session()->flash('success', "Tahapan \"{$tahapan->nama}\" berhasil diselesaikan!");
        $this->redirect(route('filament.peserta.pages.kegiatan-tahapan-page', ['kegiatanId' => $this->kegiatanId]));
    }
}
