<?php

namespace App\Filament\Resources\EvaluasiResource\Pages;

use App\Filament\Resources\EvaluasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvaluasi extends EditRecord
{
    protected static string $resource = EvaluasiResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()->label('Hapus')];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return Actions\Action::make('save')->label('Simpan perubahan')->submit('save');
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return Actions\Action::make('cancel')
            ->label('Batalkan')
            ->color('danger')
            ->url($this->getResource()::getUrl('index'));
    }
}
