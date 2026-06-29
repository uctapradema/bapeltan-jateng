<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluasiTypeResource\Pages;
use App\Models\EvaluasiType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class EvaluasiTypeResource extends Resource
{
    protected static ?string $model = EvaluasiType::class;

    protected static ?string $navigationGroup = 'EVALUASI';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Jenis Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama Jenis Evaluasi'),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(40),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluasiTypes::route('/'),
            'create' => Pages\CreateEvaluasiType::route('/create'),
            'edit' => Pages\EditEvaluasiType::route('/{record}/edit'),
        ];
    }
}
