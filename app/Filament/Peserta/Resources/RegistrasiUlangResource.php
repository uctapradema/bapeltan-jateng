<?php

namespace App\Filament\Peserta\Resources;

use App\Filament\Peserta\Resources\RegistrasiUlangResource\Pages;
use App\Models\Kegiatan;
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
                Tables\Columns\TextColumn::make('nama_pelatihan')->label('Nama Kegiatan'),
                Tables\Columns\TextColumn::make('tanggal_mulai')->label('Tanggal Mulai')->date(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->label('Tanggal Selesai')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if (isset($record->registrasiUlangs) && $record->registrasiUlangs->isNotEmpty()) {
                            return $record->registrasiUlangs->first()->status;
                        }
                        return 'belum_daftar';
                    })
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'belum_daftar' => 'Belum Daftar',
                            default => $state,
                        };
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger'  => 'ditolak',
                        'primary' => 'belum_daftar',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Daftar')
                    ->label('Daftar')
                    ->icon('heroicon-o-plus')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $user = Auth::user();
                        if (!$user || !$user->peserta) return;

                        $pesertaNik = $user->peserta->nik;

                        $exists = RegistrasiUlang::where('peserta_nik', $pesertaNik)
                            ->where('kegiatan_id', $record->id)
                            ->exists();

                        if ($exists) {
                            \Filament\Notifications\Notification::make()
                                ->title('Anda sudah mendaftar kegiatan ini.')
                                ->warning()
                                ->send();
                            return;
                        }

                        RegistrasiUlang::create([
                            'peserta_nik' => $pesertaNik,
                            'kegiatan_id' => $record->id,
                            'status' => 'pending',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Pendaftaran berhasil dikirim.')
                            ->success()
                            ->send();
                    })
                    ->visible(function ($record) {
                        $user = Auth::user();
                        if (!$user || !$user->peserta) return false;
                        $pesertaNik = $user->peserta->nik;

                        return !RegistrasiUlang::where('peserta_nik', $pesertaNik)
                            ->where('kegiatan_id', $record->id)
                            ->exists();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        if (!$user || !$user->peserta) {
            return Kegiatan::query()->whereRaw('0 = 1');
        }

        $pesertaNik = $user->peserta->nik;

        return Kegiatan::query()->with(['registrasiUlangs' => function ($q) use ($pesertaNik) {
            $q->where('peserta_nik', $pesertaNik);
        }]);
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
