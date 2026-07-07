<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnggotaKeluarga;
use App\Models\KepalaKeluarga;
use App\Http\Resources\KepalaKeluargaResource;
use App\Http\Resources\AnggotaKeluargaResource;
use App\Http\Traits\ApiResponse;

class AnggotaKeluargaController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $kepala_keluarga)
    {
        //
        $kepala = KepalaKeluarga::findOrFail($kepala_keluarga);
        $request->validate([
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|in:l,p',
            'hubungan_ke_kk' => 'required|in:kepala keluarga,istri,anak,orang tua,lainnya'
        ]);

        $anggota = $kepala->anggota()->create([
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'hubungan_ke_kk' => $request->hubungan_ke_kk
        ]);

        $anggota->load('kepala');

        return $this->success(new AnggotaKeluargaResource($anggota), 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kepala_keluarga, string $anggota)
    {
        //
        $anggota_keluarga = AnggotaKeluarga::whereHas('kepala', function ($query) use ($kepala_keluarga) {
            return $query->where('id', $kepala_keluarga);
        })->where('id', $anggota)->firstOrFail();

        return $this->success(new AnggotaKeluargaResource($anggota_keluarga), 'Data ditampilkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kepala_keluarga, string $anggota)
    {
        //
        $request->validate([
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required|in:l,p',
            'hubungan_ke_kk' => 'required|in:kepala keluarga,istri,anak,orang tua,lainnya'
        ]);

        $anggota_keluarga = AnggotaKeluarga::whereHas('kepala', function ($query) use ($kepala_keluarga) {
            return $query->where('id', $kepala_keluarga);
        })->where('id', $anggota)->firstOrFail();

        $anggota_keluarga->update($request->all());

        return $this->success(new AnggotaKeluargaResource($anggota_keluarga), 'Data Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kepala_keluarga, string $anggota)
    {
        //
        $anggota_keluarga = AnggotaKeluarga::whereHas('kepala', function ($query) use ($kepala_keluarga) {
            return $query->where('id', $kepala_keluarga);
        })->where('id', $anggota)->firstOrFail();

        $anggota_keluarga->delete();

        return $this->success(null , 'Data Berhasil Dihapus');

    }
}
