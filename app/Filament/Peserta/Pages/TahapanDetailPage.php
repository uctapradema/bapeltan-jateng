<?php

namespace App\Filament\Peserta\Pages;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\PelatihanTahapanQuestion;
use App\Services\TahapanProgressService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TahapanDetailPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Detail Tahapan';
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.peserta.pages.tahapan-detail';

    public ?string $tahapanId = null;
    public ?array $tahapan = null;
    public ?array $questions = [];
    public array $jawaban = [];
    public bool $isCompleted = false;
    public bool $isHarian = false;
    public array $riwayatHarian = [];
    public ?string $todayDate = null;

    protected static string $routePath = 'tahapan-detail';

    public static function getRoutePath(): string
    {
        return static::$routePath . '/{tahapanId}';
    }

    public function getRouteKeyName(): ?string
    {
        return 'tahapanId';
    }

    public function mount(string $tahapanId): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $this->tahapanId = $tahapanId;
        $this->todayDate = now()->format('Y-m-d');

        $tahapan = PelatihanTahapan::with(['kegiatan', 'questions' => fn ($q) => $q->orderBy('urutan')])
            ->find($tahapanId);

        if (!$tahapan) {
            session()->flash('danger', 'Tahapan tidak ditemukan.');
            $this->redirect(route('filament.peserta.pages.tahapan-list-page'));
            return;
        }

        $this->isHarian = $tahapan->tipe === 'harian';

        $progress = PelatihanTahapanProgress::where('tahapan_id', $tahapanId)
            ->where('peserta_nik', $user->peserta->nik)
            ->first();

        $this->isCompleted = !$this->isHarian && $progress && $progress->status === 'completed';
        $savedJawaban = $progress->jawaban ?? [];

        $this->tahapan = [
            'id' => $tahapan->id,
            'nama' => $tahapan->nama,
            'deskripsi' => $tahapan->deskripsi,
            'tipe' => $tahapan->tipe,
            'link' => $tahapan->link,
            'kegiatan_nama' => $tahapan->kegiatan->nama_pelatihan ?? '-',
            'kegiatan_id' => $tahapan->kegiatan_id,
        ];

        $this->questions = $tahapan->questions->map(fn ($q) => [
            'id' => $q->id,
            'pertanyaan' => $q->pertanyaan,
            'tipe' => $q->tipe,
            'opsi' => $q->opsi,
            'wajib' => $q->wajib,
            'urutan' => $q->urutan,
        ])->toArray();

        if ($this->isHarian) {
            $this->riwayatHarian = $savedJawaban['riwayat'] ?? [];
            $todayKey = $this->todayDate;
            $todayData = $this->riwayatHarian[$todayKey] ?? null;

            $this->jawaban = [];
            foreach ($this->questions as $q) {
                $qId = (string) $q['id'];
                if ($q['tipe'] === 'checkbox') {
                    $this->jawaban[$qId] = $todayData['jawaban'][$qId] ?? [];
                } else {
                    $this->jawaban[$qId] = $todayData['jawaban'][$qId] ?? null;
                }
            }
        } else {
            $this->jawaban = [];
            foreach ($this->questions as $q) {
                $qId = (string) $q['id'];
                if ($q['tipe'] === 'checkbox') {
                    $this->jawaban[$qId] = $savedJawaban[$qId] ?? [];
                } else {
                    $this->jawaban[$qId] = $savedJawaban[$qId] ?? null;
                }
            }
        }
    }

    public function setRating(string $questionId, int $value): void
    {
        $this->jawaban[$questionId] = $value;
    }

    public function simpanJawaban(): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $tahapan = PelatihanTahapan::find($this->tahapanId);
        if (!$tahapan) return;

        $questions = PelatihanTahapanQuestion::where('tahapan_id', $this->tahapanId)->get();

        foreach ($questions as $q) {
            $qId = (string) $q->id;
            $val = $this->jawaban[$qId] ?? null;
            if ($q->wajib && empty($val)) {
                session()->flash('danger', "Pertanyaan \"{$q->pertanyaan}\" wajib diisi.");
                return;
            }
        }

        if ($this->isHarian) {
            $progress = PelatihanTahapanProgress::where('tahapan_id', $this->tahapanId)
                ->where('peserta_nik', $user->peserta->nik)
                ->first();

            $existingJawaban = $progress->jawaban ?? [];
            $riwayat = $existingJawaban['riwayat'] ?? [];

            $riwayat[$this->todayDate] = [
                'jawaban' => $this->jawaban,
                'waktu' => now()->toDateTimeString(),
            ];

            $newJawaban = ['riwayat' => $riwayat];

            PelatihanTahapanProgress::updateOrCreate(
                ['tahapan_id' => $this->tahapanId, 'peserta_nik' => $user->peserta->nik],
                [
                    'jawaban' => $newJawaban,
                ]
            );

            $this->riwayatHarian = $riwayat;
            session()->flash('success', 'Daily Mood hari ini berhasil disimpan!');
        } else {
            PelatihanTahapanProgress::updateOrCreate(
                ['tahapan_id' => $this->tahapanId, 'peserta_nik' => $user->peserta->nik],
                [
                    'jawaban' => $this->jawaban,
                ]
            );

            session()->flash('success', 'Jawaban berhasil disimpan!');
        }
    }

    public function selesaikanTahapan(): void
    {
        $user = Auth::user();
        if (!$user->peserta) return;

        $tahapan = PelatihanTahapan::find($this->tahapanId);
        if (!$tahapan) return;

        if ($this->isHarian) {
            $this->simpanJawaban();
            return;
        }

        $questions = PelatihanTahapanQuestion::where('tahapan_id', $this->tahapanId)->get();

        foreach ($questions as $q) {
            $qId = (string) $q->id;
            $val = $this->jawaban[$qId] ?? null;
            if ($q->wajib && empty($val)) {
                session()->flash('danger', "Pertanyaan \"{$q->pertanyaan}\" wajib diisi.");
                return;
            }
        }

        $service = app(TahapanProgressService::class);
        $service->completeTahapan($this->tahapanId, $user->peserta->nik, $this->jawaban);

        session()->flash('success', "Tahapan \"{$tahapan->nama}\" berhasil diselesaikan!");
        $this->isCompleted = true;
    }
}
