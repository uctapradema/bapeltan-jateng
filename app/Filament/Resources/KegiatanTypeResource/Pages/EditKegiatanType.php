<?php

namespace App\Filament\Resources\KegiatanTypeResource\Pages;

use App\Filament\Resources\KegiatanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKegiatanType extends EditRecord
{
    protected static string $resource = KegiatanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
