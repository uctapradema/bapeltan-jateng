<?php

namespace App\Services;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;

class TahapanProgressService
{
    public function completeTahapan(string $tahapanId, string $pesertaId, ?array $jawaban = null): PelatihanTahapanProgress
    {
        $tahapan = PelatihanTahapan::findOrFail($tahapanId);

        $progress = PelatihanTahapanProgress::updateOrCreate(
            ['tahapan_id' => $tahapanId, 'peserta_id' => $pesertaId],
            [
                'status' => 'completed',
                'jawaban' => $jawaban,
                'completed_at' => now(),
            ]
        );

        $this->activateNextTahapan($tahapan, $pesertaId);

        return $progress;
    }

    public function activateNextTahapan(PelatihanTahapan $current, string $pesertaId): void
    {
        $next = PelatihanTahapan::where('kegiatan_id', $current->kegiatan_id)
            ->where('urutan', '>', $current->urutan)
            ->orderBy('urutan')
            ->first();

        if ($next) {
            PelatihanTahapanProgress::updateOrCreate(
                ['tahapan_id' => $next->id, 'peserta_id' => $pesertaId],
                ['status' => 'active']
            );
        }
    }
}
