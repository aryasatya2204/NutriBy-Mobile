<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API route is working!',
        'timestamp' => now()
    ]);
});

// Rute yang bisa diakses publik (tanpa token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute yang wajib menggunakan token (terproteksi)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoint sederhana untuk mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute untuk Profil Pengguna
    Route::get('/profile', [App\Http\Controllers\API\ProfileController::class, 'show']);
    Route::put('/profile', [App\Http\Controllers\API\ProfileController::class, 'update']);
    
    // Rute untuk Data Anak (menggunakan apiResource)
    Route::apiResource('/children', App\Http\Controllers\API\ChildController::class);
});