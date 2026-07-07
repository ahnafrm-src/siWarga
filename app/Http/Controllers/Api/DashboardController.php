<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\KepalaKeluarga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $totalKk = KepalaKeluarga::count();
        $anggota = AnggotaKeluarga::count();
        $laki = AnggotaKeluarga::where('jenis_kelamin', 'l')->count();
        $perempuan = AnggotaKeluarga::where('jenis_kelamin', 'p')->count();

        return response()->json([
            'total_kk' => $totalKk,
            'total_anggota' => $anggota,
            'laki_laki' => $laki,
            'perempuan' => $perempuan
        ]);
    }
}
