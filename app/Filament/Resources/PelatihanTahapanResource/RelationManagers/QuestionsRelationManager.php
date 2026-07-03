<?php

namespace App\Filament\Resources\PelatihanTahapanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function canCreate(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pertanyaan')
                    ->required()
                    ->columnSpanFull()
                    ->label('Pertanyaan'),
                Forms\Components\Select::make('tipe')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'checkbox' => 'Checkbox',
                        'text' => 'Text Singkat',
                        'textarea' => 'Text Panjang',
                        'rating' => 'Rating (1-5)',
                        'konfirmasi' => 'Konfirmasi (Ya/Tidak)',
                    ])
                    ->required()
                    ->reactive()
                    ->label('Tipe Jawaban'),
                Forms\Components\Repeater::make('opsi')
                    ->label('Opsi Jawaban')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->label('Opsi'),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Forms\Get $get) => in_array($get('tipe'), ['pilihan_ganda', 'checkbox'])),
                Forms\Components\TextInput::make('urutan')
                    ->numeric()
                    ->default(0)
                    ->label('Urutan'),
                Forms\Components\Toggle::make('wajib')
                    ->default(true)
                    ->label('Wajib Diisi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pertanyaan')
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->limit(80)
                    ->searchable()
                    ->label('Pertanyaan'),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'checkbox' => 'Checkbox',
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'rating' => 'Rating',
                        'konfirmasi' => 'Konfirmasi',
                    })
                    ->color(fn ($state) => match ($state) {
                        'pilihan_ganda' => 'primary',
                        'checkbox' => 'info',
                        'text' => 'gray',
                        'textarea' => 'gray',
                        'rating' => 'warning',
                        'konfirmasi' => 'success',
                    }),
                Tables\Columns\IconColumn::make('wajib')
                    ->boolean()
                    ->label('Wajib'),
            ])
            ->defaultSort('urutan')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Pertanyaan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
