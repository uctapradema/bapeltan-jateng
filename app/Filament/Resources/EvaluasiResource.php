<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluasiResource\Pages;
use App\Filament\Resources\EvaluasiResource\RelationManagers\QuestionsRelationManager;
use App\Models\Evaluasi;
use App\Models\Kegiatan;
use App\Models\EvaluasiType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class EvaluasiResource extends Resource
{
    protected static ?string $model = Evaluasi::class;

    protected static ?string $navigationGroup = 'EVALUASI';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kegiatan_id')
                    ->label('Kegiatan')
                    ->options(Kegiatan::pluck('nama_pelatihan', 'id'))
                    ->required(),

                Forms\Components\Select::make('evaluasi_type_id')
                    ->label('Jenis Evaluasi')
                    ->options(EvaluasiType::pluck('nama', 'id'))
                    ->required(),

                Forms\Components\TextInput::make('judul')
                    ->label('Judul Evaluasi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type.nama')
                    ->label('Jenis Evaluasi'),

                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->label('Kegiatan'),

                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('Jumlah Pertanyaan')
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluasis::route('/'),
            'create' => Pages\CreateEvaluasi::route('/create'),
            'edit' => Pages\EditEvaluasi::route('/{record}/edit'),
        ];
    }
}
