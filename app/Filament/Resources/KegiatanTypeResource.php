<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KegiatanTypeResource\Pages;
use App\Models\KegiatanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KegiatanTypeResource extends Resource
{
    protected static ?string $model = KegiatanType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'MASTER DATA';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Jenis Kegiatan';

    protected static ?string $titleAttribute = 'nama_type';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_type')
                ->required()
                ->label('Nama Jenis Kegiatan')
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_type')
                    ->label('Nama Jenis Kegiatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('nama_type');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKegiatanTypes::route('/'),
            'create' => Pages\CreateKegiatanType::route('/create'),
            'edit' => Pages\EditKegiatanType::route('/{record}/edit'),
        ];
    }
}
