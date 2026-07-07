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
        Schema::create('kepala_keluarga', function (Blueprint $table) {
            $table->id();
            $table->char('no_kk', 16)->nullable()->unique();
            $table->string('nama_kepala');
            $table->text('alamat');
            $table->string('no_hp', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_keluarga');
    }
};
