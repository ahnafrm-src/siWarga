<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KepalaKeluarga;
use App\Http\Resources\KepalaKeluargaResource;
use App\Http\Requests\StoreKepalaKeluargaRequest;

class KepalaKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $kepala = KepalaKeluarga::with('anggota')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data ditampilkan',
            'data' => KepalaKeluargaResource::collection($kepala)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKepalaKeluargaRequest $request)
    {
        //
        $validated = $request->validated();

        $kepalaKeluarga = KepalaKeluarga::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data telah berhasil ditambahkan',
            'data' => new KepalaKeluargaResource($kepalaKeluarga)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $kepala = KepalaKeluarga::with('anggota')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data ditampilan',
            'data' => new KepalaKeluargaResource($kepala)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreKepalaKeluargaRequest $request, string $id)
    {
        //
        $kepala = KepalaKeluarga::findOrFail($id);
        $validated = $request->validated();

        $kepala->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate',
            'data' => new KepalaKeluargaResource($kepala)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $kepala = KepalaKeluarga::findOrFail($id);
        $kepala->delete();

        return response()->json([
            'success' => true,
            'message' => 'data berhasil dihapus',
            'data' => null
        ]);
    }
}
