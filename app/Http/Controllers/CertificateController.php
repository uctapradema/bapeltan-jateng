<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiUlang;
use App\Models\Peserta;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download(string $registrasiId)
    {
        $registrasi = RegistrasiUlang::with(['peserta.kabupaten', 'kegiatan'])
            ->where('status', 'selesai')
            ->findOrFail($registrasiId);

        $peserta = $registrasi->peserta;
        $kegiatan = $registrasi->kegiatan;

        // Generate nomor sertifikat
        $nomor = 'BAPELTAN/' . $kegiatan->kode_pelatihan . '/' . $registrasi->created_at->format('Y');

        $pdf = Pdf::loadView('certificates.template', [
            'peserta' => $peserta,
            'kegiatan' => $kegiatan,
            'nomor' => $nomor,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("sertifikat-{$peserta->nik}-{$kegiatan->kode_pelatihan}.pdf");
    }

    public function preview(string $registrasiId)
    {
        $registrasi = RegistrasiUlang::with(['peserta.kabupaten', 'kegiatan'])
            ->where('status', 'selesai')
            ->findOrFail($registrasiId);

        $peserta = $registrasi->peserta;
        $kegiatan = $registrasi->kegiatan;

        $nomor = 'BAPELTAN/' . $kegiatan->kode_pelatihan . '/' . $registrasi->created_at->format('Y');

        $pdf = Pdf::loadView('certificates.template', [
            'peserta' => $peserta,
            'kegiatan' => $kegiatan,
            'nomor' => $nomor,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("sertifikat-{$peserta->nik}-{$kegiatan->kode_pelatihan}.pdf");
    }
}
