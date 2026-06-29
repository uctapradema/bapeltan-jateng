<?php
// app/Filament/Resources/PesertaResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'DATA';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';
    protected static ?string $navigationLabel = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Personal')
                    ->schema([
                        Forms\Components\Select::make('kabupaten_id')
                            ->relationship('kabupaten', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Kabupaten'),
                        Forms\Components\TextInput::make('nik')
                            ->required()
                            ->length(16)
                            ->unique(ignoreRecord: true)
                            ->label('NIK'),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap')
                            ->afterStateUpdated(fn ($state, $set) => $set('nama', strtoupper($state))),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->required()
                            ->maxLength(255)
                            ->label('Tempat Lahir'),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->required()
                            ->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('nomor_telepon')
                            ->tel()
                            ->label('Nomor Telepon')
                            ->nullable(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn ($livewire, $get) => !$get('user_id'))
                            ->label('Password')
                            ->minLength(8),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->label('Konfirmasi Password')
                            ->dehydrated(false)
                            ->same('password'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Data Tambahan')
                    ->schema([
                        Forms\Components\Select::make('agama')
                            ->options([
                                'ISLAM' => 'ISLAM',
                                'KRISTEN' => 'KRISTEN', 
                                'KATOLIK' => 'KATOLIK',
                                'HINDU' => 'HINDU',
                                'BUDDHA' => 'BUDDHA',
                                'KONGHUCU' => 'KONGHUCU'
                            ])
                            ->required()
                            ->label('Agama'),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'LAKI-LAKI' => 'LAKI-LAKI',
                                'PEREMPUAN' => 'PEREMPUAN'
                            ])
                            ->required()
                            ->label('Jenis Kelamin'),
                        Forms\Components\Select::make('status_pernikahan')
                            ->options([
                                'BELUM MENIKAH' => 'BELUM MENIKAH',
                                'MENIKAH' => 'MENIKAH',
                                'CERAI HIDUP' => 'CERAI HIDUP', 
                                'CERAI MATI' => 'CERAI MATI'
                            ])
                            ->required()
                            ->label('Status Pernikahan'),
                        Forms\Components\Select::make('pendidikan_terakhir')
                            ->options([
                                'SD' => 'SD', 'SMP' => 'SMP', 'SMA' => 'SMA',
                                'D1' => 'D1', 'D2' => 'D2', 'D3' => 'D3',
                                'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3'
                            ])
                            ->required()
                            ->label('Pendidikan Terakhir'),
                        Forms\Components\TextInput::make('pekerjaan')
                            ->required()
                            ->label('Pekerjaan'),
                        Forms\Components\TextInput::make('usaha_tani')
                            ->required()
                            ->label('Usaha Tani'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Alamat dan Kontak')
                    ->schema([
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->required()
                            ->columnSpanFull()
                            ->label('Alamat Lengkap')
                            ->placeholder('RT.001 RW.002 DS. GEDAWANG, KEC. SEMAMPIR, KAB. TEMANGGUNG'),
                        Forms\Components\TextInput::make('nama_poktan')
                            ->required()
                            ->label('Nama Poktan/Organisasi/Departemen'),
                        Forms\Components\Textarea::make('alamat_poktan')
                            ->required()
                            ->columnSpanFull()
                            ->label('Alamat Poktan/Organisasi/Departemen'),
                        Forms\Components\TextInput::make('nip')
                            ->nullable()
                            ->label('NIP (Opsional bagi Aparatur)'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->label('Alamat Email'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->sortable()
                    ->label('NIK'),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('kabupaten.nama')
                    ->sortable()
                    ->label('Kabupaten'),
                Tables\Columns\TextColumn::make('usia')
                    ->label('Usia')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state . ' tahun'),
                Tables\Columns\TextColumn::make('registrasi_ulang_count')
                    ->counts('registrasiUlangs')
                    ->label('Jumlah Pelatihan')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kabupaten')
                    ->relationship('kabupaten', 'nama')
                    ->label('Filter Kabupaten'),
                Tables\Filters\Filter::make('usia_maksimal')
                    ->query(fn (Builder $query) => $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 50'))
                    ->label('Memenuhi Syarat Usia (≤50 tahun)'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            //'view' => Pages\ViewPeserta::route('/{record}'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}