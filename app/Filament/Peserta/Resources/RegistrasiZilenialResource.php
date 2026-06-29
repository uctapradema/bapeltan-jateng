<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\RegistrasiZilenialResource\Pages;
use App\Filament\Peserta\Resources\RegistrasiZilenialResource\RelationManagers;
use App\Models\RegistrasiZilenial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrasiZilenialResource extends Resource
{
    protected static ?string $model = RegistrasiZilenial::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Registrasi';
    protected static ?string $navigationLabel = 'Registrasi Zilenial';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrasiZilenials::route('/'),
            'create' => Pages\CreateRegistrasiZilenial::route('/create'),
            'edit' => Pages\EditRegistrasiZilenial::route('/{record}/edit'),
        ];
    }
}
