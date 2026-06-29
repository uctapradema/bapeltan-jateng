<?php

namespace App\Filament\Widgets;

use App\Models\RegistrasiUlang;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentRegistrationsWidget extends TableWidget
{
    protected static ?string $heading = 'Registrasi Terbaru';
    protected static ?int $sort = 1;
    protected static ?string $maxHeight = '300px';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RegistrasiUlang::with(['peserta', 'kegiatan'])
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->searchable()
                    ->label('Peserta'),

                Tables\Columns\TextColumn::make('kegiatan.nama_pelatihan')
                    ->searchable()
                    ->limit(20)
                    ->label('Kegiatan'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                        'primary' => 'selesai',
                    ])
                    ->formatStateUsing(
                        fn ($state) => match ($state) {
                            'pending' => 'Pending',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak',
                            'selesai' => 'Selesai',
                            default => $state,
                        },
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label('Waktu'),
            ])
            ->paginated([5]);
    }
}
