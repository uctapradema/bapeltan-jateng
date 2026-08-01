<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use App\Models\Pengaturan;
use Filament\Notifications\Notification;

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

        $this->form->fill([
            'judul' => $this->pengaturan->judul,
            'sub_judul' => $this->pengaturan->sub_judul,
            'tanggal_tutup' => $this->pengaturan->tanggal_tutup,
            'info' => $this->pengaturan->info,
            'lokasi' => $this->pengaturan->lokasi,
            'persyaratan' => $this->pengaturan->persyaratan ?? [],
            'fasilitas' => $this->pengaturan->fasilitas ?? [],
        ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('UMUM')
                            ->schema([
                                Forms\Components\TextInput::make('judul')->required(),
                                Forms\Components\TextInput::make('sub_judul')->required(),
                                Forms\Components\DatePicker::make('tanggal_tutup')
                                    ->label('Batas Pendaftaran')
                                    ->required(),
                                Forms\Components\Textarea::make('info'),
                                Forms\Components\TextInput::make('lokasi'),
                            ]),

                        Forms\Components\Tabs\Tab::make('PERSYARATAN')
                            ->schema([
                                Forms\Components\Repeater::make('persyaratan')
                                    ->schema([
                                        Forms\Components\TextInput::make('nama')->required(),
                                    ])
                                    ->createItemButtonLabel('Tambah Persyaratan')
                                    ->columnSpan('full'),
                            ]),

                        Forms\Components\Tabs\Tab::make('FASILITAS')
                            ->schema([
                                Forms\Components\Repeater::make('fasilitas')
                                    ->schema([
                                        Forms\Components\TextInput::make('nama')->required(),
                                    ])
                                    ->createItemButtonLabel('Tambah Fasilitas')
                                    ->columnSpan('full'),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $this->pengaturan->update([
            'judul' => $state['judul'] ?? '',
            'sub_judul' => $state['sub_judul'] ?? '',
            'tanggal_tutup' => $state['tanggal_tutup'] ?? null,
            'info' => $state['info'] ?? '',
            'lokasi' => $state['lokasi'] ?? '',
            'persyaratan' => $state['persyaratan'] ?? [],
            'fasilitas' => $state['fasilitas'] ?? [],
        ]);

        Notification::make()
            ->title('Pengaturan berhasil diperbarui!')
            ->success()
            ->send();
    }
}
