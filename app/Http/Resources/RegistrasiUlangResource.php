<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrasiUlangResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'tanggal_daftar' => $this->tanggal_daftar?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'peserta' => new PesertaResource($this->whenLoaded('peserta')),
            'kegiatan' => new KegiatanResource($this->whenLoaded('kegiatan')),
        ];
    }
}
