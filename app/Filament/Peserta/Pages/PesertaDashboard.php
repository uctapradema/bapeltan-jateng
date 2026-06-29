<?php

namespace App\Filament\Peserta\Pages;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\Pengaturan;
use App\Models\RegistrasiUlang;
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

    public function getKegiatanTersedia(): array
    {
        $user = Auth::user();
        $registeredMap = [];
        if ($user->peserta) {
            $registeredMap = $user->peserta->registrasiUlangs()
                ->pluck('status', 'kegiatan_id')
                ->toArray();
        }

        return \App\Models\Kegiatan::with('kegiatanType')
            ->where('status', 'active')
            ->get()
            ->map(fn ($k) => [
                'id' => $k->id,
                'jenis' => $k->kegiatanType->nama_type ?? '-',
                'kode' => $k->kode_pelatihan,
                'nama' => $k->nama_pelatihan,
                'mulai' => $k->tanggal_mulai->format('d M Y'),
                'selesai' => $k->tanggal_selesai->format('d M Y'),
                'kuota' => $k->kuota,
                'terdaftar' => $k->jumlah_peserta_diterima,
                'kuota_tersedia' => $k->kuota_tersedia,
                'status' => $k->status,
                'status_daftar' => $registeredMap[$k->id] ?? null,
            ])
            ->values()
            ->toArray();
    }

    public function daftarKegiatan(int $kegiatanId): void
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            session()->flash('danger', 'Akun peserta tidak ditemukan.');
            return;
        }

        $kegiatan = \App\Models\Kegiatan::find($kegiatanId);
        if (!$kegiatan) {
            session()->flash('danger', 'Kegiatan tidak ditemukan.');
            return;
        }

        if (!$kegiatan->kuota_tersedia) {
            session()->flash('danger', 'Kuota kegiatan ini sudah penuh.');
            return;
        }

        $alreadyRegistered = RegistrasiUlang::where('peserta_nik', $peserta->nik)
            ->where('kegiatan_id', $kegiatanId)
            ->exists();

        if ($alreadyRegistered) {
            session()->flash('danger', 'Anda sudah terdaftar di kegiatan ini.');
            return;
        }

        RegistrasiUlang::create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatanId,
            'kegiatan_type_id' => $kegiatan->kegiatan_type_id,
            'tahun' => now()->year,
            'status' => 'pending',
        ]);

        session()->flash('success', "Berhasil mendaftar kegiatan \"{$kegiatan->nama_pelatihan}\"!");
    }

    public function getTahapanData(): array
    {
        $user = Auth::user();
        if (!$user->peserta) return [];

        $acceptedKegiatans = $user->peserta->registrasiUlangs()
            ->where('status', 'diterima')
            ->with(['kegiatan.tahapans'])
            ->get();

        $result = [];
        foreach ($acceptedKegiatans as $reg) {
            $kegiatan = $reg->kegiatan;
            if (!$kegiatan || $kegiatan->tahapans->isEmpty()) continue;

            $progressMap = PelatihanTahapanProgress::where('peserta_nik', $user->peserta->nik)
                ->whereIn('tahapan_id', $kegiatan->tahapans->pluck('id'))
                ->pluck('status', 'tahapan_id')
                ->toArray();

            $tahapans = $kegiatan->tahapans->sortBy('urutan')->map(function ($t) use ($progressMap) {
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

            $total = count($tahapans);
            $completed = collect($tahapans)->where('status', 'completed')->count();
            $persentase = $total > 0 ? round(($completed / $total) * 100) : 0;

            $result[] = [
                'kegiatan_id' => $kegiatan->id,
                'nama' => $kegiatan->nama_pelatihan,
                'kode' => $kegiatan->kode_pelatihan,
                'tahapans' => array_values($tahapans),
                'total' => $total,
                'completed' => $completed,
                'persentase' => $persentase,
            ];
        }

        return $result;
    }

    public function selesaikanTahapan(int $tahapanId): void
    {
        $user = Auth::user();
        $peserta = $user->peserta;
        if (!$peserta) return;

        $tahapan = PelatihanTahapan::find($tahapanId);
        if (!$tahapan) return;

        $progress = PelatihanTahapanProgress::updateOrCreate(
            ['tahapan_id' => $tahapanId, 'peserta_nik' => $peserta->nik],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        $this->activateNextTahapan($tahapan, $peserta->nik);

        session()->flash('success', "Tahapan \"{$tahapan->nama}\" berhasil diselesaikan!");
    }

    private function activateNextTahapan(PelatihanTahapan $current, string $pesertaNik): void
    {
        $next = PelatihanTahapan::where('kegiatan_id', $current->kegiatan_id)
            ->where('urutan', '>', $current->urutan)
            ->orderBy('urutan')
            ->first();

        if ($next) {
            PelatihanTahapanProgress::updateOrCreate(
                ['tahapan_id' => $next->id, 'peserta_nik' => $pesertaNik],
                ['status' => 'active']
            );
        }
    }
}
