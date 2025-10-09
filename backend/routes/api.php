<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GrowthController; 
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AllergyController;
use App\Http\Controllers\API\MenuController;

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
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    
    // Rute untuk Data Anak (menggunakan apiResource)
    Route::apiResource('/children', App\Http\Controllers\API\ChildController::class);
    Route::post('/children/{child}/growth-records', [GrowthController::class, 'store']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/income', [ProfileController::class, 'updateIncome']);
    Route::get('/allergy-facts/search', [AllergyController::class, 'searchFacts']);
    Route::get('/menus/search', [MenuController::class, 'search']);
});