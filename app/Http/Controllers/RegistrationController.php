<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kegiatan;
use App\Models\Pengaturan;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function showForm()
    {
        $pengaturan  = Pengaturan::first();
        $kabupatens  = Kabupaten::orderBy('nama')->get();
        $kegiatans   = Kegiatan::aktif()->get();

        return view('register', compact('pengaturan', 'kabupatens', 'kegiatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kabupaten_id'       => 'required|exists:kabupatens,id',
            'nik'                => 'required|digits:16|unique:pesertas,nik',
            'nama'               => 'required|string|max:255',
            'tempat_lahir'       => 'required|string|max:255',
            'tanggal_lahir'      => 'required|date',
            'jenis_kelamin'      => 'required|in:LAKI-LAKI,PEREMPUAN',
            'nomor_telepon'      => 'required|string|max:15',
            'agama'              => 'required|in:ISLAM,KRISTEN,KATOLIK,HINDU,BUDDHA,KONGHUCU',
            'status_pernikahan'  => 'required|in:BELUM MENIKAH,MENIKAH,CERAI HIDUP,CERAI MATI',
            'pendidikan_terakhir'=> 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'pekerjaan'          => 'required|string|max:255',
            'usaha_tani'         => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'alamat_lengkap'     => 'required|string',
            'nama_poktan'        => 'required|string|max:255',
            'alamat_poktan'      => 'required|string',
            'nip'                => 'nullable|string|max:30',
            'password'           => 'required|min:6|confirmed',
        ], [
            'kabupaten_id.required'        => 'Kabupaten wajib dipilih.',
            'kabupaten_id.exists'          => 'Kabupaten tidak valid.',
            'nik.required'                 => 'NIK wajib diisi.',
            'nik.digits'                   => 'NIK harus tepat 16 digit angka.',
            'nik.unique'                   => 'NIK ini sudah terdaftar.',
            'nama.required'                => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required'        => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'       => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih.',
            'nomor_telepon.required'       => 'Nomor telepon wajib diisi.',
            'agama.required'               => 'Agama wajib dipilih.',
            'status_pernikahan.required'   => 'Status pernikahan wajib dipilih.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'pekerjaan.required'           => 'Pekerjaan wajib diisi.',
            'usaha_tani.required'          => 'Komoditas / usaha tani wajib diisi.',
            'email.required'               => 'Email wajib diisi.',
            'email.email'                  => 'Format email tidak valid.',
            'alamat_lengkap.required'      => 'Alamat lengkap wajib diisi.',
            'nama_poktan.required'         => 'Nama poktan wajib diisi.',
            'alamat_poktan.required'       => 'Alamat poktan wajib diisi.',
            'password.required'            => 'Password wajib diisi.',
            'password.min'                 => 'Password minimal 6 karakter.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
        ]);

        $usia = \Carbon\Carbon::parse($validated['tanggal_lahir'])->age;
        if ($usia < 18) {
            return back()->withInput()->withErrors(['tanggal_lahir' => 'Usia minimal 18 tahun (lahir sebelum ' . now()->subYears(18)->format('d M Y') . ').']);
        }
        if ($usia > 50) {
            return back()->withInput()->withErrors(['tanggal_lahir' => 'Usia maksimal 50 tahun (lahir setelah ' . now()->subYears(50)->format('d M Y') . ').']);
        }

        try {
            $peserta = Peserta::updateOrCreate(
                ['nik' => $validated['nik']],
                [
                    'kabupaten_id'        => $validated['kabupaten_id'],
                    'nik'                 => $validated['nik'],
                    'nama'                => strtoupper($validated['nama']),
                    'tempat_lahir'        => $validated['tempat_lahir'],
                    'tanggal_lahir'       => $validated['tanggal_lahir'],
                    'nomor_telepon'       => $validated['nomor_telepon'],
                    'agama'               => $validated['agama'],
                    'jenis_kelamin'       => $validated['jenis_kelamin'],
                    'status_pernikahan'   => $validated['status_pernikahan'],
                    'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                    'pekerjaan'           => $validated['pekerjaan'],
                    'usaha_tani'          => $validated['usaha_tani'],
                    'alamat_lengkap'      => $validated['alamat_lengkap'],
                    'nama_poktan'         => $validated['nama_poktan'],
                    'alamat_poktan'       => $validated['alamat_poktan'],
                    'nip'                 => $validated['nip'] ?? null,
                    'email'               => $validated['email'],
                ]
            );

            if (!$peserta->user_id) {
                $user = User::create([
                    'name'              => strtoupper($validated['nama']),
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role'              => 'peserta',
                    'email_verified_at' => now(),
                ]);
                $peserta->user_id = $user->id;
                $peserta->save();
            }

            return redirect()->route('public.registration')->with('success', 'Biodata dan akun berhasil dibuat! Silakan login.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
