<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnggotaKeluargaExport;
use App\Models\AnggotaKeluarga;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;
use Illuminate\Support\Str;


class ExportController extends Controller
{
    //
    public function excel(Request $request)
    {

        $jenis_kelamin = null;
        $kelompok_usia = null;

        // $query = AnggotaKeluarga::query();

        if ($request->filled('kelompok_usia')) {
            $kelompok_usia = $request->kelompok_usia;
        }

        if ($request->filled('jenis_kelamin')) {
            $jenis_kelamin = $request->jenis_kelamin;
        }

        // $anggota = $query->get();

        return (new AnggotaKeluargaExport($jenis_kelamin, $kelompok_usia))->download('data_warga_' . Carbon::now()->timestamp . '.xlsx');
    }

    public function pdf(Request $request, PdfService $pdf)
    {
        $jenis_kelamin = null;
        $kelompok_usia = null;
        $nama_kepala = null;

        if ($request->filled('kelompok_usia')) {
            $kelompok_usia = Str::ucfirst($request->kelompok_usia);
        }

        if ($request->filled('jenis_kelamin')) {
            $jenis_kelamin = $request->jenis_kelamin;
        }

        if ($request->filled('nama_kepala')) {
            $nama_kepala = $request->nama_kepala;
        }

        $query = AnggotaKeluarga::query()
            ->when($jenis_kelamin, function ($query, $v) {
                return $query->where('jenis_kelamin', $v);
            })
            ->when($nama_kepala, function ($query, $v) {
                return $query->whereHas('kepala', function ($q) use ($v) {
                    $q->where('nama_kepala', 'like', "%$v%");
                });
            })->with('kepala')->get();

        $anggota = $query->when($kelompok_usia, function ($q, $v) {
            return $q->filter(function ($item) use ($v) {
                return $item->kelompokUsia == $v;
            });
        });

        if ($jenis_kelamin == 'l') {
            $jenis_kelamin = 'laki-laki';
        } else if ($jenis_kelamin == 'p') {
            $jenis_kelamin = "perempuan";
        }

        return $pdf->exportData($anggota, ['jenis_kelamin' => $jenis_kelamin, 'kelompok_usia' => $kelompok_usia, 'nama_kepala' => $nama_kepala])->download('data_warga' . Carbon::now()->timestamp . '.pdf');

        // return $anggota;
    }
}
