<?php
namespace App\Livewire;

use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicTrainingRegistration extends Component implements HasForms
{
    use InteractsWithForms;

    public ?string $nik = null;
    public ?array $data = [];

    public function mount($nik)
    {
        $this->nik = $nik;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $kegiatanOptions = Kegiatan::aktif()->pluck('nama_pelatihan', 'id')->toArray();

        return $form
            ->schema([
                Select::make('kegiatan_id')
                    ->label('Pilih Jenis Pelatihan')
                    ->options($kegiatanOptions)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function register(): void
    {
        try {
            DB::transaction(function () {
                $data = $this->form->getState();

                $peserta = Peserta::where('nik', $this->nik)->firstOrFail();

                $exists = Pendaftaran::where('peserta_nik', $peserta->nik)
                    ->where('kegiatan_id', $data['kegiatan_id'])
                    ->exists();

                if ($exists) {
                    throw new \Exception('Anda sudah terdaftar pada pelatihan ini.');
                }

                $kegiatan = Kegiatan::findOrFail($data['kegiatan_id']);
                if ($kegiatan->jumlah_peserta_diterima >= $kegiatan->kuota) {
                    throw new \Exception('Kuota untuk pelatihan ini sudah penuh.');
                }

                Pendaftaran::create([
                    'peserta_nik' => $peserta->nik,
                    'kegiatan_id' => $kegiatan->id,
                    'status' => 'pending',
                ]);

                session()->flash('success', 'Pendaftaran pelatihan berhasil! Tunggu verifikasi admin.');
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mendaftar pelatihan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.public-training-registration');
    }
}
