<?php

namespace App\Livewire;

use App\Models\Kabupaten;
use App\Models\Pengaturan;
use App\Models\Peserta;
use Livewire\Component;

class PublicRegistrationForm extends Component
{
    public $pengaturan;

    // Data Personal
    public $kabupaten_id = '';
    public $nik = '';
    public $nama = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $nomor_telepon = '';
    public $password = '';
    public $password_confirmation = '';

    // Data Tambahan
    public $agama = '';
    public $jenis_kelamin = '';
    public $status_pernikahan = '';
    public $pendidikan_terakhir = '';
    public $pekerjaan = '';
    public $usaha_tani = '';

    // Alamat dan Kontak
    public $alamat_lengkap = '';
    public $nama_poktan = '';
    public $alamat_poktan = '';
    public $nip = '';
    public $email = '';

    protected $rules = [
        'kabupaten_id' => 'required|exists:kabupatens,id',
        'nik' => 'required|size:16|digits:16|unique:pesertas,nik',
        'nama' => 'required|string|max:255',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'nomor_telepon' => 'required|string|max:15',
        'password' => 'required|min:6|confirmed',
        'agama' => 'required',
        'jenis_kelamin' => 'required',
        'status_pernikahan' => 'required',
        'pendidikan_terakhir' => 'required',
        'pekerjaan' => 'required|string|max:255',
        'usaha_tani' => 'required|string|max:255',
        'alamat_lengkap' => 'required|string',
        'nama_poktan' => 'required|string|max:255',
        'alamat_poktan' => 'required|string',
        'email' => 'required|email|max:255',
    ];

    protected $messages = [
        'nik.required' => 'NIK wajib diisi.',
        'nik.size' => 'NIK harus tepat 16 digit.',
        'nik.digits' => 'NIK harus berupa angka.',
        'nik.unique' => 'NIK ini sudah terdaftar.',
        'kabupaten_id.required' => 'Kabupaten wajib dipilih.',
        'nama.required' => 'Nama lengkap wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'agama.required' => 'Agama wajib dipilih.',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'status_pernikahan.required' => 'Status pernikahan wajib dipilih.',
        'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function mount()
    {
        $this->pengaturan = Pengaturan::first();
    }

    public function updatedNama()
    {
        $this->nama = strtoupper($this->nama);
    }

    public function create()
    {
        $this->validate();

        $usia = now()->diffInYears($this->tanggal_lahir);
        if ($usia < 18) {
            session()->flash('error', 'Usia minimal 18 tahun.');
            return;
        }
        if ($usia > 50) {
            session()->flash('error', 'Usia maksimal 50 tahun.');
            return;
        }

        try {
            $peserta = Peserta::updateOrCreate(
                ['nik' => $this->nik],
                [
                    'kabupaten_id' => $this->kabupaten_id,
                    'nik' => $this->nik,
                    'nama' => strtoupper($this->nama),
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'nomor_telepon' => $this->nomor_telepon,
                    'agama' => $this->agama,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'status_pernikahan' => $this->status_pernikahan,
                    'pendidikan_terakhir' => $this->pendidikan_terakhir,
                    'pekerjaan' => $this->pekerjaan,
                    'usaha_tani' => $this->usaha_tani,
                    'alamat_lengkap' => $this->alamat_lengkap,
                    'nama_poktan' => $this->nama_poktan,
                    'alamat_poktan' => $this->alamat_poktan,
                    'nip' => $this->nip ?: null,
                    'email' => $this->email,
                ]
            );

            if (!$peserta->user_id) {
                $user = \App\Models\User::create([
                    'name' => strtoupper($this->nama),
                    'email' => $this->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($this->password),
                    'role' => 'peserta',
                    'email_verified_at' => now(),
                ]);
                $peserta->user_id = $user->id;
                $peserta->save();
            }

            session()->flash('success', 'Biodata dan akun berhasil dibuat! Silakan login.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.public-registration-form')
            ->layout('livewire.layouts.public');
    }
}
