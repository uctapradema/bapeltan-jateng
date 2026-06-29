<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KegiatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_pelatihan' => $this->nama_pelatihan,
            'kode_pelatihan' => $this->kode_pelatihan,
            'tanggal_mulai' => $this->tanggal_mulai?->format('Y-m-d'),
            'tanggal_selesai' => $this->tanggal_selesai?->format('Y-m-d'),
            'kuota' => $this->kuota,
            'status' => $this->status,
            'deskripsi' => $this->deskripsi,
            'jumlah_peserta' => $this->jumlah_peserta_diterima,
            'kuota_tersedia' => $this->kuota_tersedia,
            'kegiatan_type' => new KegiatanTypeResource($this->whenLoaded('kegiatanType')),
        ];
    }
}
