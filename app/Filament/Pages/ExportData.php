<?php

namespace App\Filament\Pages;

use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Filament\Pages\Page;

class ExportData extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.export-data';

    public function getTitle(): string
    {
        return "Export Data";
    }

    public function exportPeserta()
    {
        $filename = 'data-peserta-' . date('Y-m-d') . '.csv';

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['NIK', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'No. HP', 'Agama', 'Jenis Kelamin', 'Status Nikah', 'Pendidikan', 'Pekerjaan', 'Usaha Tani', 'Alamat', 'Poktan', 'Kabupaten', 'Email', 'NIP']);

            Peserta::with('kabupaten')->chunk(100, function ($pesertas) use ($handle) {
                foreach ($pesertas as $p) {
                    fputcsv($handle, [
                        $p->nik,
                        $p->nama,
                        $p->tempat_lahir,
                        $p->tanggal_lahir,
                        $p->nomor_telepon,
                        $p->agama,
                        $p->jenis_kelamin,
                        $p->status_pernikahan,
                        $p->pendidikan_terakhir,
                        $p->pekerjaan,
                        $p->usaha_tani,
                        $p->alamat_lengkap,
                        $p->nama_poktan,
                        $p->kabupaten->nama ?? '-',
                        $p->email,
                        $p->nip,
                    ]);
                }
            });

            fclose($handle);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function exportRegistrasi()
    {
        $filename = 'data-registrasi-' . date('Y-m-d') . '.csv';

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['No', 'NIK Peserta', 'Nama Peserta', 'Kegiatan', 'Status', 'Catatan', 'Tanggal Daftar']);

            RegistrasiUlang::with(['peserta', 'kegiatan'])->chunk(100, function ($registrasis) use ($handle) {
                $no = 1;
                foreach ($registrasis as $reg) {
                    fputcsv($handle, [
                        $no++,
                        $reg->peserta->nik ?? '-',
                        $reg->peserta->nama ?? '-',
                        $reg->kegiatan->nama_pelatihan ?? '-',
                        $reg->status,
                        $reg->catatan ?? '-',
                        $reg->created_at?->format('d/m/Y H:i'),
                    ]);
                }
            });

            fclose($handle);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream($callback, 200, $headers);
    }
}
