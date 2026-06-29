<?php

namespace App\Filament\Resources\EvaluasiResource\Pages;

use App\Filament\Resources\EvaluasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluasi extends CreateRecord
{
    protected static string $resource = EvaluasiResource::class;

    public function getTitle(): string
    {
        return 'Tambah Evaluasi';
    }

    public function getHeading(): string
    {
        return 'Tambah Evaluasi';
    }
}
