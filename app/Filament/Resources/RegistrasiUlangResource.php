<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrasiUlangResource\Pages;
use App\Filament\Resources\RegistrasiUlangResource\RelationManagers;
use App\Models\RegistrasiUlang;
use App\Notifications\RegistrationStatusNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrasiUlangResource extends Resource
{
    protected static ?string $model = RegistrasiUlang::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'DATA';
    protected static ?string $navigationLabel = 'Registrasi Ulang';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_nik')->relationship('peserta', 'nama')->searchable()->preload()->required()->reactive()->label('Peserta'),
            Forms\Components\Select::make('kegiatan_id')->relationship('kegiatan', 'nama_pelatihan')->searchable()->preload()->required()->label('Kegiatan Pelatihan'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ])
                ->required()
                ->label('Status Pendaftaran'),
            Forms\Components\DatePicker::make('tanggal_selesai_pelatihan')->label('Tanggal Selesai Pelatihan')->nullable(),
            Forms\Components\FileUpload::make('sertifikat_path')
                ->label('Upload Sertifikat')
                ->directory('sertifikats')
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                ->maxSize(5120) // 5MB
                ->nullable()
                ->helperText('Format: PDF, JPG, PNG (Maksimal 5MB)'),
            Forms\Components\Textarea::make('catatan_sertifikat')->label('Catatan Sertifikat')->nullable()->helperText('Catatan tambahan mengenai sertifikat'),
            Forms\Components\Textarea::make('catatan')->columnSpanFull()->label('Catatan Pendaftaran'),
        ]);
    }

    // Update table method untuk menampilkan kolom sertifikat
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nik')->searchable()->label('NIK'),
                Tables\Columns\TextColumn::make('peserta.nama')->searchable()->sortable()->label('Nama Peserta'),
                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')->searchable()->sortable()->label('Kegiatan'),
                Tables\Columns\TextColumn::make('kegiatan.kode_pelatihan')->label('Kode')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_daftar')->dateTime()->sortable()->label('Tanggal Daftar'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                        'primary' => 'selesai',
                    ])
                    ->formatStateUsing(
                        fn($state) => match ($state) {
                            'pending' => 'Pending',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'selesai' => 'Selesai',
                            default => $state,
                        },
                    ),
                Tables\Columns\IconColumn::make('sertifikat_path')->label('Sertifikat')->boolean()->trueIcon('heroicon-o-document-text')->trueColor('success')->falseIcon('heroicon-o-x-circle')->falseColor('danger'),
                Tables\Columns\TextColumn::make('tanggal_selesai_pelatihan')->date()->sortable()->label('Selesai')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kegiatan')->relationship('kegiatan', 'nama_pelatihan')->label('Kegiatan'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('memiliki_sertifikat')->label('Memiliki Sertifikat')->query(fn($query) => $query->whereNotNull('sertifikat_path')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_sertifikat')->label('Download')->icon('heroicon-o-arrow-down-tray')->url(fn($record) => $record->sertifikat_url)->openUrlInNewTab()->visible(fn($record) => !empty($record->sertifikat_path)),
                Tables\Actions\Action::make('terima')
                    ->action(function (RegistrasiUlang $record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => 'diterima']);
                        $record->peserta->user->notify(
                            new RegistrationStatusNotification($record, $oldStatus, 'diterima')
                        );
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (RegistrasiUlang $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('tolak')
                    ->action(function (RegistrasiUlang $record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => 'ditolak']);
                        $record->peserta->user->notify(
                            new RegistrationStatusNotification($record, $oldStatus, 'ditolak')
                        );
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (RegistrasiUlang $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('selesai')
                    ->action(function (RegistrasiUlang $record) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => 'selesai',
                            'tanggal_selesai_pelatihan' => now(),
                        ]);
                        $record->peserta->user->notify(
                            new RegistrationStatusNotification($record, $oldStatus, 'selesai')
                        );
                    })
                    ->requiresConfirmation()
                    ->color('primary')
                    ->icon('heroicon-o-flag')
                    ->visible(fn (RegistrasiUlang $record) => $record->status === 'diterima'),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListRegistrasiUlangs::route('/'),
            'create' => Pages\CreateRegistrasiUlang::route('/create'),
            'edit' => Pages\EditRegistrasiUlang::route('/{record}/edit'),
        ];
    }
}
