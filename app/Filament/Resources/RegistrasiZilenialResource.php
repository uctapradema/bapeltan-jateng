<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrasiZilenialResource\Pages;
use App\Models\RegistrasiZilenial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrasiZilenialResource extends Resource
{
    protected static ?string $model = RegistrasiZilenial::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'DATA';
    protected static ?string $navigationLabel = 'Registrasi Zilenial';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_id')
                ->relationship('peserta', 'nama')
                ->searchable()
                ->preload()
                ->required()
                ->label('Peserta'),
            Forms\Components\Select::make('kegiatan_id')
                ->relationship('kegiatan', 'nama_pelatihan')
                ->searchable()
                ->preload()
                ->required()
                ->label('Kegiatan'),
            Forms\Components\TextInput::make('tahun')
                ->required()
                ->numeric()
                ->label('Tahun'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ])
                ->required()
                ->label('Status'),
            Forms\Components\Textarea::make('catatan')
                ->nullable()
                ->label('Catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Peserta'),
                Tables\Columns\TextColumn::make('peserta.nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->searchable()
                    ->sortable()
                    ->label('Kegiatan'),
                Tables\Columns\TextColumn::make('tahun')
                    ->sortable()
                    ->label('Tahun'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->label('Status'),
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
            'index' => Pages\ListRegistrasiZilenials::route('/'),
            'create' => Pages\CreateRegistrasiZilenial::route('/create'),
            'edit' => Pages\EditRegistrasiZilenial::route('/{record}/edit'),
        ];
    }
}
