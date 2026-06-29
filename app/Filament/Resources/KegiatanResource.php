<?php
// app/Filament/Resources/KegiatanResource.php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kegiatan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KegiatanType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KegiatanResource\Pages;

class KegiatanResource extends Resource
{
    protected static ?string $model = Kegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'DATA';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Kegiatan';
    protected static ?string $pluralModelLabel = 'Kegiatan';
    protected static ?string $navigationLabel = 'Kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kegiatan_type_id')
                    ->label('Jenis Kegiatan')
                    ->required()
                    ->relationship('kegiatanType', 'nama_type')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_type')
                        ->label('Nama Jenis Kegiatan')
                        ->required()
                        ->maxLength(255)
                    ])
                    ->createOptionAction(function ($action) {
                        $action->action(function (array $data, $action) {
                            $exists = KegiatanType::where('nama_type', $data['nama_type'])->exists();
                    
                            if ($exists) {
                                Notification::make()
                                    ->title("Jenis Kegiatan '{$data['nama_type']}' sudah ada.")
                                    ->danger()
                                    ->send();
                                return null;
                            }
                    
                            $record = KegiatanType::create($data);
                    
                            Notification::make()
                                ->title("Jenis Kegiatan '{$record->nama_type}' berhasil dibuat!")
                                ->success()
                                ->send();
                    
                            return $record->id;
                        });
                    }),
                TextInput::make('nama_pelatihan')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Pelatihan'),
                TextInput::make('kode_pelatihan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Kode Pelatihan')
                    ->placeholder('Contoh: PMA-7, PP-9'),
                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->label('Tanggal Mulai'),
                DatePicker::make('tanggal_selesai')
                    ->required()
                    ->label('Tanggal Selesai'),
                TextInput::make('kuota')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->label('Kuota Peserta'),
                Select::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif'
                    ])
                    ->required()
                    ->label('Status'),
                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->label('Deskripsi Pelatihan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kegiatanType.nama_type')
                    ->label('Jenis Kegiatan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_pelatihan')
                    ->sortable()
                    ->searchable()
                    ->label('Kode'),
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pelatihan'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable()
                    ->label('Mulai'),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable()
                    ->label('Selesai'),
                Tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_peserta_diterima')
                    ->label('Terdaftar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota_tersedia')
                    ->label('Kuota Tersedia')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state ? 'Tersedia' : 'Penuh'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Aktif' : 'Nonaktif'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Nonaktif'
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('kuota_tersedia')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('(SELECT COUNT(*) FROM pendaftarans WHERE pendaftarans.kegiatan_id = kegiatans.id AND status = "diterima") < kegiatans.kuota'))
                    ->label('Kuota Masih Tersedia'),
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
            'index' => Pages\ListKegiatans::route('/'),
            'create' => Pages\CreateKegiatan::route('/create'),
            //'view' => Pages\ViewKegiatan::route('/{record}'),
            'edit' => Pages\EditKegiatan::route('/{record}/edit'),
        ];
    }
}