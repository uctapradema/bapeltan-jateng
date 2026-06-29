<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\RegistrasiUlangResource\Pages;
use App\Models\RegistrasiUlang;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RegistrasiUlangResource extends Resource
{
    protected static ?string $model = RegistrasiUlang::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Registrasi Ulang';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')->label('Nama Kegiatan')->searchable(),
                Tables\Columns\TextColumn::make('kegiatan.kode_pelatihan')->label('Kode')->searchable(),
                Tables\Columns\TextColumn::make('kegiatan.tanggal_mulai')->label('Mulai')->date(),
                Tables\Columns\TextColumn::make('kegiatan.tanggal_selesai')->label('Selesai')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                        'primary' => 'selesai',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Daftar')->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        if (!$user || !$user->peserta) {
            return RegistrasiUlang::query()->whereRaw('0 = 1');
        }

        return RegistrasiUlang::query()
            ->with('kegiatan')
            ->where('peserta_nik', $user->peserta->nik);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrasiUlangs::route('/'),
            'create' => Pages\CreateRegistrasiUlang::route('/create'),
            'edit' => Pages\EditRegistrasiUlang::route('/{record}/edit'),
        ];
    }
}
