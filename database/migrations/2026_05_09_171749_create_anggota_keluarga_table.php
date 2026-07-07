<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kk_id')->nullable()->constrained('kepala_keluarga')->cascadeOnDelete();
            $table->string('nama');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['l', 'p']);
            $table->enum('hubungan_ke_kk', ['kepala keluarga', 'istri', 'anak', 'orang tua', 'lainnya']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
    }
};
