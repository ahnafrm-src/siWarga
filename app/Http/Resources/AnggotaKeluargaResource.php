<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AnggotaKeluargaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->jenis_kelamin == 'l') {
            $jenis = 'laki-laki';
        } else {
            $jenis = 'perempuan';
        };
        $kepala = $this->kepala;
        return [
            'kepala_keluarga' => $kepala->nama_kepala,
            'nama' => $this->nama,
            'tanggal_lahir' => $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->format('d-m-Y') : null,
            'kelompok_usia' => $this->kelompokUsia,
            'jenis_kelamin' => $jenis,
            'hubungan_ke_kk' => $this->hubungan_ke_kk
        ];
    }
}
