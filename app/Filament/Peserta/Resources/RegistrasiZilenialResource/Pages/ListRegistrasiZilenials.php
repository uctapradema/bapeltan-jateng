<?php

namespace App\Filament\Peserta\Resources\RegistrasiZilenialResource\Pages;

use App\Filament\Peserta\Resources\RegistrasiZilenialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasiZilenials extends ListRecords
{
    protected static string $resource = RegistrasiZilenialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
