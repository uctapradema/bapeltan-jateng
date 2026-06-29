<?php

namespace App\Filament\Resources\EvaluasiTypeResource\Pages;

use App\Filament\Resources\EvaluasiTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluasiType extends CreateRecord
{
    protected static string $resource = EvaluasiTypeResource::class;

    public function getHeading(): string
    {
        return 'Tambah Jenis Evaluasi';
    }
}
