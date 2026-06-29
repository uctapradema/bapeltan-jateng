<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreatePeserta extends CreateRecord
{
    protected static string $resource = PesertaResource::class;

    protected ?string $temporaryPassword = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->temporaryPassword = $data['password'] ?? null;

        unset($data['password']);
        unset($data['password_confirmation']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->temporaryPassword)) {
            $user = User::create([
                'name' => $this->record->nama,
                'email' => $this->record->email,
                'password' => Hash::make($this->temporaryPassword),
                'role' => 'peserta',
            ]);

            $this->record->user_id = $user->id;
            $this->record->save();
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Peserta';
    }

    public function getHeading(): string
    {
        return 'Tambah Peserta';
    }
}
