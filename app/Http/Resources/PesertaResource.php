<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesertaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'nik' => $this->nik,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir?->format('Y-m-d'),
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
            'nip' => $this->nip,
            'email' => $this->email,
            'kabupaten' => new KabupatenResource($this->whenLoaded('kabupaten')),
            'kegiatan' => KegiatanResource::collection($this->whenLoaded('kegiatans')),
        ];
    }
}
