<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\GroupResource\Pages;
use App\Models\Group;
use App\Models\RegistrasiUlang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Group';
    protected static ?string $modelLabel = 'Group';
    protected static ?string $pluralModelLabel = 'Group';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Group')->sortable(),
                Tables\Columns\TextColumn::make('group_link')->label('Link')->limit(35),
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')->label('Kegiatan'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->date()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Lihat Group')
                    ->url(fn($record) => $record->group_link)
                    ->icon('heroicon-o-link')
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();

        if (!$user || !$user->peserta) {
            return parent::getEloquentQuery()->whereRaw('0 = 1');
        }

        $pesertaNik = $user->peserta->nik;

        $kegiatanIds = RegistrasiUlang::where('peserta_nik', $pesertaNik)
            ->where('status', 'diterima')
            ->pluck('kegiatan_id')
            ->toArray();

        return parent::getEloquentQuery()->whereIn('kegiatan_id', $kegiatanIds);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
        ];
    }
}
