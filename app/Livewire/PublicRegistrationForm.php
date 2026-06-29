<?php
// app/Livewire/PublicRegistrationForm.php

namespace App\Livewire;

use App\Models\Kabupaten;
use App\Models\Pengaturan;
use App\Models\Peserta;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicRegistrationForm extends Component implements HasForms
{
    use InteractsWithForms;

    public array $kabupatenOptions = [];
    public $pengaturan;
    public ?array $data = [];

    public function mount(): void
    {
        $this->kabupatenOptions = Kabupaten::pluck('nama', 'id')->toArray();
        $this->form->fill();
        $this->pengaturan = Pengaturan::first();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Data Personal
                \Filament\Forms\Components\Section::make('Data Personal')
                    ->schema([
                        Select::make('kabupaten_id')
                            ->live()
                            ->label('Kabupaten')
                            ->options($this->kabupatenOptions)
                            ->required(),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->length(16)
                            ->unique('pesertas', 'nik')
                            ->validationMessages([
                                'unique' => 'NIK ini sudah terdaftar. Silakan gunakan NIK lain atau hubungi admin.',
                            ]),

                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(fn($state, $set) => $set('nama', strtoupper($state))),

                        TextInput::make('tempat_lahir')->label('Tempat Lahir')->required(),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->maxDate(now()->subYears(18))
                            ->validationMessages(['max' => 'Usia minimal 18 tahun.']),

                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->required()
                            ->tel()
                            ->maxLength(15),                        

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->minLength(6)
                            ->same('password_confirmation'),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required(),
                    ])->columns(2),

                // Section 2: Data Tambahan
                \Filament\Forms\Components\Section::make('Data Tambahan')
                    ->schema([
                        Select::make('agama')->label('Agama')->options([
                            'ISLAM'=>'ISLAM','KRISTEN'=>'KRISTEN','KATOLIK'=>'KATOLIK',
                            'HINDU'=>'HINDU','BUDDHA'=>'BUDDHA','KONGHUCU'=>'KONGHUCU'
                        ])->required(),

                        Select::make('jenis_kelamin')->label('Jenis Kelamin')->options([
                            'LAKI-LAKI'=>'LAKI-LAKI','PEREMPUAN'=>'PEREMPUAN'
                        ])->required(),

                        Select::make('status_pernikahan')->label('Status Pernikahan')->options([
                            'BELUM MENIKAH'=>'BELUM MENIKAH','MENIKAH'=>'MENIKAH',
                            'CERAI HIDUP'=>'CERAI HIDUP','CERAI MATI'=>'CERAI MATI'
                        ])->required(),

                        Select::make('pendidikan_terakhir')->label('Pendidikan Terakhir')->options([
                            'SD'=>'SD','SMP'=>'SMP','SMA'=>'SMA','D1'=>'D1','D2'=>'D2','D3'=>'D3',
                            'S1'=>'S1','S2'=>'S2','S3'=>'S3'
                        ])->required(),

                        TextInput::make('pekerjaan')->label('Pekerjaan')->required(),
                        TextInput::make('usaha_tani')->label('Usaha Tani')->required(),
                    ])->columns(3),

                // Section 3: Alamat dan Kontak
                \Filament\Forms\Components\Section::make('Alamat dan Kontak')
                    ->schema([
                        Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap (Sesuai Identitas Kependudukan)')
                            ->required()
                            ->placeholder('Contoh: RT.001 RW.002 DS. GEDAWANG, KEC. SEMAMPIR, KAB. TEMANGGUNG')
                            ->columnSpanFull(),

                        TextInput::make('nama_poktan')->label('Nama Poktan/Organisasi/Departemen')->required(),
                        Textarea::make('alamat_poktan')->label('Alamat Poktan/Organisasi/Departemen')->required()->columnSpanFull(),
                        TextInput::make('nip')->label('NIP (Opsional bagi Aparatur)')->nullable(),
                        TextInput::make('email')->label('Alamat Email')->email()->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    /**
     * Simpan biodata peserta saja
     */
    public function create(): void
    {
        try {
            DB::transaction(function () {
                $data = $this->form->getState();

                $data['nama'] = strtoupper($data['nama']);
                $usia = now()->diffInYears($data['tanggal_lahir']);
                if ($usia > 50) {
                    throw new \Exception('Usia peserta melebihi batas maksimal 50 tahun.');
                }

                $peserta = Peserta::updateOrCreate(
                    ['nik' => $data['nik']],
                    $data
                );

                if (!$peserta->user_id) {
                    $user = \App\Models\User::create([
                        'name' => $data['nama'],
                        'email' => $data['email'],
                        'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                        'role' => 'peserta',
                        'email_verified_at' => now(),
                    ]);

                    $peserta->user_id = $user->id;
                    $peserta->save();
                }

                session()->flash('success', 'Biodata dan akun berhasil dibuat! Silakan login.');
                $this->form->fill();
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan biodata: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.public-registration-form')
            ->layout('livewire.layouts.public');
    }
}
