<?php

namespace App\Filament\Resources\PelatihanTahapanResource\Pages;

use App\Filament\Resources\PelatihanTahapanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPelatihanTahapan extends EditRecord
{
    protected static string $resource = PelatihanTahapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
