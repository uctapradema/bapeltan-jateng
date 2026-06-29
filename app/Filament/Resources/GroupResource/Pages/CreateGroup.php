<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGroup extends CreateRecord
{
    protected static string $resource = GroupResource::class;

    public function getTitle(): string
    {
        return 'Tambah Group';
    }

    public function getHeading(): string
    {
        return 'Tambah Group';
    }
}
