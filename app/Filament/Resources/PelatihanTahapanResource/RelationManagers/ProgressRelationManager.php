<?php

namespace App\Filament\Resources\PelatihanTahapanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'progress';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Peserta'),
                Tables\Columns\TextColumn::make('peserta.nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'locked' => 'Terkunci',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'active' => 'info',
                        'completed' => 'success',
                        'locked' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Selesai Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jawaban_summary')
                    ->label('Jawaban')
                    ->limit(80)
                    ->state(fn ($record) => $this->getJawabanSummary($record)),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'locked' => 'Terkunci',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_jawaban')
                    ->label('Lihat Jawaban')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading(fn ($record) => 'Jawaban: ' . $record->peserta->nama)
                    ->modalContent(fn ($record) => view('filament.pages.laporan-jawaban', [
                        'record' => $record,
                        'jawaban' => $record->jawaban,
                        'tahapan' => $record->tahapan,
                    ]))
                    ->modalSubmitAction(false),
            ])
            ->bulkActions([]);
    }

    private function getJawabanSummary($record): string
    {
        $jawaban = $record->jawaban;
        if (!$jawaban) return '-';

        $tahapan = $record->tahapan;
        if ($tahapan && $tahapan->tipe === 'harian') {
            $riwayat = $jawaban['riwayat'] ?? [];
            return count($riwayat) . ' hari terisi';
        }

        $count = count(array_filter($jawaban, fn ($v) => !empty($v)));
        return $count . ' jawaban';
    }
}
