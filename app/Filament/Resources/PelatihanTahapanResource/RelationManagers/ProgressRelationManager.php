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
                    ->modalContent(fn ($record) => $this->getJawabanModalContent($record))
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

    private function getJawabanModalContent($record): string
    {
        $jawaban = $record->jawaban;
        if (!$jawaban) {
            return '<p class="text-gray-500">Belum ada jawaban.</p>';
        }

        $tahapan = $record->tahapan;
        if ($tahapan && $tahapan->tipe === 'harian') {
            return $this->renderHarianJawaban($jawaban);
        }

        return $this->renderSekaliJawaban($jawaban, $record->tahapan_id);
    }

    private function renderHarianJawaban($jawaban): string
    {
        $riwayat = $jawaban['riwayat'] ?? [];
        if (empty($riwayat)) {
            return '<p class="text-gray-500">Belum ada riwayat harian.</p>';
        }

        $html = '<div class="space-y-4">';
        krsort($riwayat);

        foreach ($riwayat as $date => $entry) {
            $waktu = $entry['waktu'] ?? '-';
            $answers = $entry['jawaban'] ?? [];
            $html .= '<div class="border rounded-lg p-3">';
            $html .= '<div class="flex items-center justify-between mb-2">';
            $html .= '<span class="font-semibold text-sm">' . \Carbon\Carbon::parse($date)->format('d M Y') . '</span>';
            $html .= '<span class="text-xs text-gray-400">' . $waktu . '</span>';
            $html .= '</div>';
            $html .= $this->renderAnswers($answers);
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function renderSekaliJawaban($jawaban, $tahapanId): string
    {
        return $this->renderAnswers($jawaban);
    }

    private function renderAnswers($answers): string
    {
        $questions = \App\Models\PelatihanTahapanQuestion::whereIn('id', array_keys($answers))->get()->keyBy('id');

        $html = '<div class="space-y-2">';
        foreach ($answers as $qId => $value) {
            $question = $questions[$qId] ?? null;
            $qLabel = $question->pertanyaan ?? "Pertanyaan #{$qId}";
            $tipe = $question->tipe ?? 'text';

            if ($tipe === 'checkbox' && is_array($value)) {
                $display = implode(', ', $value);
            } elseif ($tipe === 'rating') {
                $display = str_repeat('★', $value) . str_repeat('☆', 5 - $value) . " ({$value}/5)";
            } else {
                $display = $value;
            }

            $html .= '<div class="flex gap-2">';
            $html .= '<span class="text-xs font-medium text-gray-500 min-w-[120px]">' . e($qLabel) . ':</span>';
            $html .= '<span class="text-sm text-gray-900 dark:text-white">' . e($display) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
