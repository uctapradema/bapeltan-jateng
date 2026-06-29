<?php

use App\Http\Requests\Api\CekNikRequest;
use App\Http\Requests\Api\DaftarPelatihanRequest;
use App\Http\Resources\PesertaResource;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\RegistrasiUlang;
use Illuminate\Support\Facades\Route;

// API untuk cek NIK peserta ============================
Route::get('/cek-nik', function (CekNikRequest $request) {
    $peserta = Peserta::with(['registrasiUlangs.kegiatan', 'kabupaten'])
        ->where('nik', $request->nik)
        ->first();

    if (!$peserta) {
        return response()->json([
            'success' => false,
            'message' => 'NIK belum terdaftar sebagai peserta. Silakan daftar biodata terlebih dahulu.',
        ], 404);
    }

    $registrasi = $peserta->registrasiUlangs->map(
        fn ($reg) => [
            'id' => $reg->id,
            'nama' => $reg->kegiatan->nama_pelatihan,
            'kode' => $reg->kegiatan->kode_pelatihan,
            'mulai' => $reg->kegiatan->tanggal_mulai->format('d-m-Y'),
            'selesai' => $reg->kegiatan->tanggal_selesai->format('d-m-Y'),
            'status' => $reg->status,
        ],
    );

    return response()->json([
        'success' => true,
        'data' => [
            'nik' => $peserta->nik,
            'nama' => $peserta->nama,
            'alamat' => $peserta->alamat_lengkap,
            'poktan' => $peserta->nama_poktan,
            'kabupaten' => $peserta->kabupaten->name ?? null,
            'registrasi' => $registrasi,
        ],
    ]);
})->middleware('throttle:30,1');

// API untuk daftar pelatihan ============================
Route::post('/daftar-pelatihan', function (DaftarPelatihanRequest $request) {
    try {
        $peserta = Peserta::where('nik', $request->nik)->firstOrFail();
        $kegiatan = Kegiatan::with('kegiatanType')->findOrFail($request->kegiatan_id);

        // Cek duplikasi
        $alreadyRegistered = RegistrasiUlang::where('peserta_nik', $peserta->nik)
            ->where('kegiatan_id', $kegiatan->id)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar pada pelatihan ini.',
            ], 409);
        }

        // Cek konflik jadwal
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

        // Buat registrasi
        $registrasi = RegistrasiUlang::create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatan->id,
            'kegiatan_type_id' => $kegiatan->kegiatan_type_id,
            'tahun' => $kegiatan->tanggal_mulai->format('Y'),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran pelatihan berhasil! Tunggu verifikasi admin.',
            'data' => [
                'registrasi_id' => $registrasi->id,
                'kegiatan' => $kegiatan->nama_pelatihan,
                'status' => 'pending',
            ],
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mendaftar pelatihan.',
        ], 500);
    }
})->middleware('throttle:10,1');
