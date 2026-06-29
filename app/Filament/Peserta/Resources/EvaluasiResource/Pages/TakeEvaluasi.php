<?php

namespace App\Filament\Peserta\Resources\EvaluasiResource\Pages;

use Filament\Forms;
use App\Models\Evaluasi;
use App\Models\RegistrasiUlang;
use App\Models\EvaluasiResponse;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Peserta\Resources\EvaluasiResource;

class TakeEvaluasi extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = EvaluasiResource::class;
    protected static string $view = 'filament.peserta.pages.take-evaluasi';

    public Evaluasi $record;
    public RegistrasiUlang $registrasiUlang;
    public array $answers = [];

    public function mount(Evaluasi $record)
    {
        $this->record = $record;

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Anda harus login untuk mengakses halaman ini.');
        }

        $peserta = $user->peserta;
        if (!$peserta) {
            abort(403, 'Anda belum memiliki data peserta.');
        }

        $registrasiUlang = RegistrasiUlang::where('peserta_nik', $peserta->nik)->where('kegiatan_id', $this->record->kegiatan_id)->where('status', 'diterima')->first();

        if (!$registrasiUlang) {
            abort(403, 'Anda tidak terdaftar pada kegiatan ini.');
        }

        $this->registrasiUlang = $registrasiUlang;

        $already = EvaluasiResponse::where('evaluasi_id', $this->record->id)->where('registrasi_ulang_id', $registrasiUlang->id)->exists();

        if ($already) {
            Notification::make()->title('Anda sudah mengisi evaluasi ini.')->warning()->send();

            return redirect(EvaluasiResource::getUrl('index'));
        }

        foreach ($this->record->questions as $question) {
            $this->answers[$question->id] = $question->tipe_jawaban === 'checkbox' ? [] : null;
        }
    }

    protected function getFormSchema(): array
    {
        return $this->record->questions
            ->map(function ($question) {
                switch ($question->tipe_jawaban) {
                    case 'radio':
                        return Radio::make("answers.{$question->id}")
                            ->label($question->pertanyaan)
                            ->options($question->options->pluck('value', 'id')->toArray())
                            ->required();
                    case 'checkbox':
                        return CheckboxList::make("answers.{$question->id}")
                            ->label($question->pertanyaan)
                            ->options($question->options->pluck('value', 'id')->toArray())
                            ->required();
                    case 'scale':
                        return Forms\Components\Select::make("answers.{$question->id}")
                            ->label($question->pertanyaan)
                            ->options([
                                1 => 'Sangat Kurang',
                                2 => 'Kurang',
                                3 => 'Cukup',
                                4 => 'Baik',
                                5 => 'Sangat Baik',
                            ])
                            ->required();
                    default:
                        return Forms\Components\Textarea::make("answers.{$question->id}")
                            ->label($question->pertanyaan)
                            ->required();
                }
            })
            ->toArray();
    }

    public function submit()
    {
        foreach ($this->answers as $questionId => $answer) {
            if (is_array($answer)) {
                $answer = json_encode($answer);
            }

            EvaluasiResponse::updateOrCreate(
                [
                    'registrasi_ulang_id' => $this->registrasiUlang->id,
                    'question_id' => $questionId,
                ],
                [
                    'evaluasi_id' => $this->record->id,
                    'jawaban' => $answer,
                ],
            );
        }

        Notification::make()->title('Jawaban berhasil dikirim')->success()->send();

        return redirect(EvaluasiResource::getUrl('index'));
    }
}
