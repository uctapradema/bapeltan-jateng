<?php

namespace App\Filament\Peserta\Pages;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title = 'Profil Saya';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.peserta.pages.profile';

    public array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        $this->form->fill([
            'nama' => $peserta->nama ?? $user->name,
            'email' => $peserta->email ?? $user->email,
            'nomor_telepon' => $peserta->nomor_telepon ?? '',
            'alamat_lengkap' => $peserta->alamat_lengkap ?? '',
            'nama_poktan' => $peserta->nama_poktan ?? '',
            'alamat_poktan' => $peserta->alamat_poktan ?? '',
            'pekerjaan' => $peserta->pekerjaan ?? '',
            'usaha_tani' => $peserta->usaha_tani ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Personal')->schema([
                    TextInput::make('nama')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Lengkap'),

                    TextInput::make('email')
                        ->email()
                        ->maxLength(255)
                        ->label('Email'),

                    TextInput::make('nomor_telepon')
                        ->tel()
                        ->required()
                        ->maxLength(20)
                        ->label('No. Telepon'),
                ])->columns(2),

                Section::make('Alamat & Kontak')->schema([
                    Textarea::make('alamat_lengkap')
                        ->rows(3)
                        ->required()
                        ->label('Alamat Lengkap'),

                    TextInput::make('nama_poktan')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Poktan'),

                    TextInput::make('alamat_poktan')
                        ->required()
                        ->maxLength(255)
                        ->label('Alamat Poktan'),
                ])->columns(2),

                Section::make('Pekerjaan')->schema([
                    TextInput::make('pekerjaan')
                        ->required()
                        ->maxLength(255)
                        ->label('Pekerjaan'),

                    TextInput::make('usaha_tani')
                        ->required()
                        ->maxLength(255)
                        ->label('Usaha Tani'),
                ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            Notification::make()
                ->title('Error')
                ->body('Data peserta tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();

        $peserta->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'],
            'alamat_lengkap' => $data['alamat_lengkap'],
            'nama_poktan' => $data['nama_poktan'],
            'alamat_poktan' => $data['alamat_poktan'],
            'pekerjaan' => $data['pekerjaan'],
            'usaha_tani' => $data['usaha_tani'],
        ]);

        // Update user name if changed
        if ($user->name !== $data['nama']) {
            $user->update(['name' => $data['nama']]);
        }

        Notification::make()
            ->title('Profil Berhasil Diperbarui')
            ->success()
            ->send();
    }
}
