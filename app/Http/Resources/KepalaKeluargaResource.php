<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AnggotaKeluargaResource;

class KepalaKeluargaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $anggota = $this->anggota;
        $method = $request->route()->getActionMethod();

        if ($method == 'index') {
            return [
                'no_kk' => $this->no_kk,
                'nama_kepala' => $this->nama_kepala,
                'alamat' => $this->alamat,
                'no_hp' => $this->no_hp,
                'jumlah_anggota' => $anggota->count(),
            ];
        };

        return [
            'no_kk' => $this->no_kk,
            'nama_kepala' => $this->nama_kepala,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'jumlah_anggota' => $anggota->count(),
            'anggota_keluarga' => AnggotaKeluargaResource::collection($anggota)
        ];
    }
}
