<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use App\Models\Pengaturan;
use Forms\Contracts\HasForms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;

class Pengaturans extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan';

    public string $judul = '';
    public string $sub_judul = '';
    public $tanggal_tutup;
    public string $info = '';
    public string $lokasi = '';
    public array $persyaratan = [];
    public array $fasilitas = [];

    public Pengaturan $pengaturan;

    public function mount(): void
    {
        $this->pengaturan = Pengaturan::firstOrCreate([], [
            'judul' => '',
            'sub_judul' => '',
            'tanggal_tutup' => now(),
            'info' => '',
            'lokasi' => '',
            'persyaratan' => [],
            'fasilitas' => [],
        ]);

        $this->judul = $this->pengaturan->judul;
        $this->sub_judul = $this->pengaturan->sub_judul;
        $this->tanggal_tutup = $this->pengaturan->tanggal_tutup;
        $this->info = $this->pengaturan->info;
        $this->lokasi = $this->pengaturan->lokasi;
        $this->persyaratan = $this->pengaturan->persyaratan ?? [];
        $this->fasilitas = $this->pengaturan->fasilitas ?? [];
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('UMUM')
                        ->schema([
                            TextInput::make('judul')->required(),
                            TextInput::make('sub_judul')->required(),
                            DatePicker::make('tanggal_tutup')
                                ->label('Batas Pendaftaran')
                                ->required(),
                            Textarea::make('info'),
                            TextInput::make('lokasi'),
                        ]),

                    Tabs\Tab::make('PERSYARATAN')
                        ->schema([
                            Repeater::make('persyaratan')
                                ->schema([
                                    TextInput::make('nama')->required(),
                                ])
                                ->createItemButtonLabel('Tambah Persyaratan')
                                ->columnSpan('full'),
                        ]),

                    Tabs\Tab::make('FASILITAS')
                        ->schema([
                            Repeater::make('fasilitas')
                                ->schema([
                                    TextInput::make('nama')->required(),
                                ])
                                ->createItemButtonLabel('Tambah Fasilitas')
                                ->columnSpan('full'),
                        ]),
                ]),
        ];
    }

    public function save(): void
    {
        $this->pengaturan->update([
            'judul' => $this->judul,
            'sub_judul' => $this->sub_judul,
            'tanggal_tutup' => $this->tanggal_tutup,
            'info' => $this->info,
            'lokasi' => $this->lokasi,
            'persyaratan' => $this->persyaratan,
            'fasilitas' => $this->fasilitas,
        ]);

        Notification::make()
            ->title('Pengaturan berhasil diperbarui!')
            ->success()
            ->send();
    }
}
