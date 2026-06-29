<?php

namespace App\Filament\Resources\EvaluasiTypeResource\Pages;

use App\Filament\Resources\EvaluasiTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluasiTypes extends ListRecords
{
    protected static string $resource = EvaluasiTypeResource::class;

    public function getTitle(): string
    {
        return 'Daftar Jenis Evaluasi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah'),
        ];
    }
}
