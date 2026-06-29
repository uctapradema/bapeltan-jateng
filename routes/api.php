<?php

use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\RegistrasiUlang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API untuk cek NIK peserta ============================
Route::get('/cek-nik', function (Request $request) {
    $nik = $request->query('nik');

    if (!$nik || strlen($nik) != 16 || !ctype_digit($nik)) {
        return response()->json([
            'success' => false,
            'message' => 'NIK tidak valid. Panjang NIK harus 16 digit angka.',
        ], 422);
    }

    $peserta = Peserta::with('registrasiUlangs.kegiatan')->where('nik', $nik)->first();

    if (!$peserta) {
        return response()->json([
            'success' => false,
            'message' => 'NIK belum terdaftar sebagai peserta. Silakan daftar biodata terlebih dahulu.',
        ], 404);
    }

    $kegiatan = $peserta->registrasiUlangs->map(
        fn($p) => [
            'nama' => $p->kegiatan->nama_pelatihan,
            'kode' => $p->kegiatan->kode_pelatihan,
            'mulai' => $p->kegiatan->tanggal_mulai->format('d-m-Y'),
            'selesai' => $p->kegiatan->tanggal_selesai->format('d-m-Y'),
            'status' => $p->status,
        ],
    );

    return response()->json([
        'success' => true,
        'data' => [
            'nik' => $peserta->nik,
            'nama' => $peserta->nama,
            'alamat' => $peserta->alamat_lengkap,
            'poktan' => $peserta->nama_poktan,
            'kegiatan' => $kegiatan,
        ],
    ]);
})->middleware('throttle:30,1');

// API untuk daftar pelatihan ============================
Route::post('/daftar-pelatihan', function (Request $request) {
    $validated = $request->validate([
        'nik' => 'required|digits:16',
        'kegiatan_id' => 'required|exists:kegiatans,id',
    ]);

    try {
        $peserta = Peserta::where('nik', $validated['nik'])->firstOrFail();
        $kegiatan = Kegiatan::with('kegiatanType')->findOrFail($validated['kegiatan_id']);

        // Cek duplikasi: satu peserta hanya bisa mendaftar satu kegiatan yang sama
        $alreadyRegistered = RegistrasiUlang::where('peserta_nik', $peserta->nik)
            ->where('kegiatan_id', $kegiatan->id)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar pada pelatihan ini.',
            ], 409);
        }

        // Cek konflik jadwal: peserta tidak boleh mendaftar pelatihan yang tanggalnya overlap
        $tanggalMulai = $kegiatan->tanggal_mulai->toDateString();
        $tanggalSelesai = $kegiatan->tanggal_selesai->toDateString();

        $conflict = RegistrasiUlang::where('peserta_nik', $peserta->nik)
            ->whereHas('kegiatan', function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                    $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                        ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                        ->orWhere(function ($qq) use ($tanggalMulai, $tanggalSelesai) {
                            $qq->where('tanggal_mulai', '<=', $tanggalMulai)
                                ->where('tanggal_selesai', '>=', $tanggalSelesai);
                        });
                });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa mendaftar pelatihan pada tanggal yang sama dengan pelatihan lain.',
            ], 409);
        }

        // Cek kuota tersedia
        $jumlahDiterima = $kegiatan->registrasiUlangs()->where('status', 'diterima')->count();
        if ($jumlahDiterima >= $kegiatan->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota untuk pelatihan ini sudah penuh.',
            ], 409);
        }

        // Buat registrasi dengan kegiatan_type_id dan tahun otomatis
        RegistrasiUlang::create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatan->id,
            'kegiatan_type_id' => $kegiatan->kegiatan_type_id,
            'tahun' => $kegiatan->tanggal_mulai->format('Y'),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran pelatihan berhasil! Tunggu verifikasi admin.',
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mendaftar pelatihan.',
        ], 500);
    }
})->middleware('throttle:10,1');
