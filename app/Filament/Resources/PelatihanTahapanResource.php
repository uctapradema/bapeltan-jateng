<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelatihanTahapanResource\Pages;
use App\Models\PelatihanTahapan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihanTahapanResource extends Resource
{
    protected static ?string $model = PelatihanTahapan::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'DATA';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Tahapan Pelatihan';
    protected static ?string $modelLabel = 'Tahapan Pelatihan';
    protected static ?string $pluralModelLabel = 'Tahapan Pelatihan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kegiatan_id')
                ->relationship('kegiatan', 'nama_pelatihan')
                ->searchable()
                ->preload()
                ->required()
                ->label('Kegiatan'),
            Forms\Components\TextInput::make('nama')
                ->required()
                ->maxLength(255)
                ->label('Nama Tahapan'),
            Forms\Components\Textarea::make('deskripsi')
                ->nullable()
                ->columnSpanFull()
                ->label('Deskripsi'),
            Forms\Components\TextInput::make('urutan')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(20)
                ->label('Urutan'),
            Forms\Components\Select::make('tipe')
                ->options([
                    'sekali' => 'Sekali',
                    'harian' => 'Harian',
                ])
                ->required()
                ->label('Tipe'),
            Forms\Components\TextInput::make('link')
                ->nullable()
                ->maxLength(500)
                ->label('Link (opsional)')
                ->helperText('URL Grup WhatsApp atau link eksternal lainnya'),
            Forms\Components\Toggle::make('wajib')
                ->default(true)
                ->label('Wajib'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->searchable()
                    ->sortable()
                    ->label('Kegiatan'),
                Tables\Columns\TextColumn::make('urutan')
                    ->sortable()
                    ->label('Urutan'),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Tahapan'),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'harian' ? 'Harian' : 'Sekali')
                    ->color(fn ($state) => $state === 'harian' ? 'warning' : 'primary'),
                Tables\Columns\IconColumn::make('wajib')
                    ->boolean()
                    ->label('Wajib'),
            ])
            ->defaultSort('urutan')
            ->filters([
                Tables\Filters\SelectFilter::make('kegiatan')
                    ->relationship('kegiatan', 'nama_pelatihan')
                    ->label('Kegiatan'),
            ])
            ->actions([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelatihanTahapans::route('/'),
            'create' => Pages\CreatePelatihanTahapan::route('/create'),
            'edit' => Pages\EditPelatihanTahapan::route('/{record}/edit'),
        ];
    }
}
