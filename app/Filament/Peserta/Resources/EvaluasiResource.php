<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\EvaluasiResource\Pages;
use App\Models\Evaluasi;
use App\Models\RegistrasiUlang;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class EvaluasiResource extends Resource
{
    protected static ?string $model = Evaluasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Evaluasi';

    // Form kosong
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // Table menampilkan evaluasi peserta
    public static function table(Table $table): Table
    {
        return $table
            ->columns([Tables\Columns\TextColumn::make('judul')->label('Judul Evaluasi')->sortable(), Tables\Columns\TextColumn::make('type.nama')->label('Jenis Evaluasi'), Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')->label('Kegiatan')])
            ->filters([])
            ->actions([Tables\Actions\Action::make('Ikuti Evaluasi')->url(fn($record) => Pages\TakeEvaluasi::getUrl(['record' => $record]))->icon('heroicon-o-pencil')])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluasis::route('/'),
            'take' => Pages\TakeEvaluasi::route('/{record}/take'),
        ];
    }

}
