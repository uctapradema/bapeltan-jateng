<?php

namespace App\Notifications;

use App\Models\RegistrasiUlang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public RegistrasiUlang $registrasi,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nama = $this->registrasi->peserta->nama ?? 'Peserta';
        $kegiatan = $this->registrasi->kegiatan->nama_pelatihan ?? 'Pelatihan';

        $statusText = match ($this->newStatus) {
            'diterima' => 'DITERIMA',
            'ditolak' => 'DITOLAK',
            'selesai' => 'SELESAI',
            default => strtoupper($this->newStatus),
        };

        $color = match ($this->newStatus) {
            'diterima' => 'success',
            'ditolak' => 'danger',
            'selesai' => 'info',
            default => 'primary',
        };

        return (new MailMessage)
            ->subject("Status Pendaftaran Pelatihan: {$statusText}")
            ->greeting("Halo {$nama},")
            ->line("Status pendaftaran Anda untuk pelatihan **{$kegiatan}** telah **{$statusText}**.")
            ->line("**Kode Pelatihan:** {$this->registrasi->kegiatan->kode_pelatihan}")
            ->line("**Tanggal Mulai:** {$this->registrasi->kegiatan->tanggal_mulai->format('d M Y')}")
            ->line("**Tanggal Selesai:** {$this->registrasi->kegiatan->tanggal_selesai->format('d M Y')}")
            ->action('Lihat Dashboard', url('/peserta'))
            ->line('Terima kasih telah menggunakan layanan Bapeltan Jateng.');
    }

    public function toArray(object $notifiable): array
    {
        $kegiatan = $this->registrasi->kegiatan->nama_pelatihan ?? 'Pelatihan';

        return [
            'registrasi_id' => $this->registrasi->id,
            'kegiatan_nama' => $kegiatan,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Status pendaftaran {$kegiatan} telah diubah menjadi {$this->newStatus}.",
        ];
    }
}
