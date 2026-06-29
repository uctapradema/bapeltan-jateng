<?php

namespace App\Filament\Resources\EvaluasiResource\Pages;

use App\Filament\Resources\EvaluasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluasis extends ListRecords
{
    protected static string $resource = EvaluasiResource::class;

    public function getTitle(): string
    {
        return 'Daftar Evaluasi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah'),
        ];
    }
}
