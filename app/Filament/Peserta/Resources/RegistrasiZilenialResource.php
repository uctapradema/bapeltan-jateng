<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\RegistrasiZilenialResource\Pages;
use App\Models\Kegiatan;
use App\Models\RegistrasiZilenial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrasiZilenialResource extends Resource
{
    protected static ?string $model = RegistrasiZilenial::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Registrasi';
    protected static ?string $navigationLabel = 'Registrasi Zilenial';
    protected static ?string $slug = 'registrasi-zilenial';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $peserta = $user->peserta;

        if (! $peserta) {
            return static::getModel()::query()->whereRaw('0 = 1');
        }

        return static::getModel()::query()
            ->where('peserta_nik', $peserta->nik);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kegiatan_id')
                    ->label('Kegiatan')
                    ->options(fn () => Kegiatan::where('status', 'active')->pluck('nama_pelatihan', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->default(date('Y'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->default('pending')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3)
                    ->placeholder('Masukkan catatan jika ada...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'info',
                    }),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                //
            ]);
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
