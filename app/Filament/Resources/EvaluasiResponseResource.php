<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluasiResponseResource\Pages;
use App\Models\EvaluasiResponse;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EvaluasiResponseResource extends Resource
{
    protected static ?string $model = EvaluasiResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'EVALUASI';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Hasil Evaluasi';
    protected static ?string $pluralModelLabel = 'Hasil Evaluasi';
    protected static ?string $navigationLabel = 'Monitoring Evaluasi';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registrasiUlang.peserta.nama')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('registrasiUlang.peserta.nik')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('evaluasi.judul')
                    ->label('Evaluasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('registrasiUlang.kegiatan.nama_pelatihan')
                    ->label('Kegiatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jawaban')
                    ->label('Jawaban')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('evaluasi')
                    ->relationship('evaluasi', 'judul')
                    ->label('Filter Evaluasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluasiResponses::route('/'),
        ];
    }
}
