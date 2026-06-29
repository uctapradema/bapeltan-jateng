<?php

namespace App\Filament\Resources\RegistrasiUlangResource\Pages;

use App\Filament\Resources\RegistrasiUlangResource;
use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrasiUlang extends EditRecord
{
    protected static string $resource = RegistrasiUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record->status === 'diterima') {
            $firstTahapan = PelatihanTahapan::where('kegiatan_id', $record->kegiatan_id)->where('urutan', 1)->first();
            if ($firstTahapan && !PelatihanTahapanProgress::where('tahapan_id', $firstTahapan->id)->where('peserta_nik', $record->peserta_nik)->exists()) {
                PelatihanTahapanProgress::create([
                    'tahapan_id' => $firstTahapan->id,
                    'peserta_nik' => $record->peserta_nik,
                    'status' => 'active',
                ]);
            }
        }
    }
}
