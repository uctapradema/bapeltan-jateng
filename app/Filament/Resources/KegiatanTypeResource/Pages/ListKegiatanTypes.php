<?php

namespace App\Filament\Resources\KegiatanTypeResource\Pages;

use App\Filament\Resources\KegiatanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKegiatanTypes extends ListRecords
{
    protected static string $resource = KegiatanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
