<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class AnggotaKeluarga extends Model
{
    //
    protected $table = "anggota_keluarga";
    protected $fillable = ['kk_id', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'hubungan_ke_kk'];

    public function kepala(){
        return $this->belongsTo(KepalaKeluarga::class, 'kk_id');
    }

    public function kelompokUsia(): Attribute {
        return Attribute::make(
        get: function () {

            $kelompok = "";
            if(!$this->tanggal_lahir){
                $kelompok = "Tidak diketahui";
                return $kelompok;
            }
            $tanggallahir =  Carbon::parse($this->tanggal_lahir);

            $usiaAsli = $tanggallahir->diffInYears(now());
            $usia = floor($usiaAsli);

            if($usia >= 60){
               $kelompok = "Lansia";
            } else if($usia <= 59 && $usia >= 18){
                $kelompok = "Dewasa";
            } else if($usia <= 17 && $usia >= 13){
                $kelompok = "Remaja";
            } else if($usia <= 12 && $usia >= 0){
                $kelompok = "Anak-anak";
            }

            return $kelompok;

            }
        );
    }
}
