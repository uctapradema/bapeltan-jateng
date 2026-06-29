<?php

namespace App\Filament\Resources\RegistrasiUlangResource\Pages;

use App\Filament\Resources\RegistrasiUlangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasiUlangs extends ListRecords
{
    protected static string $resource = RegistrasiUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah'),
        ];
    }

    public function getTitle(): string
    {
        return "Registrasi Ulang";
    }
}
