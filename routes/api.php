<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KepalaKeluargaController;
use App\Http\Controllers\Api\AnggotaKeluargaController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    // Route::get('kepala-keluarga', [KepalaKeluargaController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::apiResource('kepala-keluarga', KepalaKeluargaController::class);
        Route::apiResource('/kepala-keluarga.anggota', AnggotaKeluargaController::class)
            ->except(['index'])->parameters(['anggota' => 'anggota']);
    });
});
