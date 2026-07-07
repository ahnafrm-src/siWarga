<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaKeluarga extends Model
{
    //
    protected $table = "kepala_keluarga";
    protected $fillable = ['no_kk', 'nama_kepala', 'alamat', 'no_hp'];

    public function anggota(){
        return $this->hasMany(AnggotaKeluarga::class, 'kk_id');
    }
}
