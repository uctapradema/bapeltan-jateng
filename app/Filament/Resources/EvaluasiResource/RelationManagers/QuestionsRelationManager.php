<?php

namespace App\Filament\Resources\EvaluasiResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $title = 'Pertanyaan Evaluasi';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('pertanyaan')
                ->label('Pertanyaan')
                ->required()
                ->rows(3),

            Forms\Components\Select::make('tipe_jawaban')
                ->label('Jenis Jawaban')
                ->options([
                    'text' => 'Teks',
                    'radio' => 'Pilihan Tunggal',
                    'checkbox' => 'Pilihan Ganda',
                    'scale' => 'Skala',
                ])
                ->required(),

            Forms\Components\Repeater::make('options')
                ->label('Opsi Jawaban')
                ->relationship('options')    // <---- perbaikan penting
                ->hidden(fn($get) => !in_array($get('tipe_jawaban'), ['radio', 'checkbox']))
                ->schema([
                    Forms\Components\TextInput::make('value')
                        ->label('Opsi')
                        ->required(),

                    Forms\Components\Checkbox::make('is_correct')
                        ->label('Benar?')
                        ->default(false),
                ])
                ->columns(1),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan')->limit(50),
                Tables\Columns\TextColumn::make('tipe_jawaban'),
                Tables\Columns\TextColumn::make('options_count')
                    ->counts('options')
                    ->label('Jumlah Opsi'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Pertanyaan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
