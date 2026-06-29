<?php

namespace App\Filament\Peserta\Resources\RegistrasiUlangResource\Pages;

use App\Filament\Peserta\Resources\RegistrasiUlangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasiUlangs extends ListRecords
{
    protected static string $resource = RegistrasiUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
