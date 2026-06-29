<?php

namespace App\Filament\Resources\RegistrasiUlangResource\Pages;

use App\Filament\Resources\RegistrasiUlangResource;
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
}
