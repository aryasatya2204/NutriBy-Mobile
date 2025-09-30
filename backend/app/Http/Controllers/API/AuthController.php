<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HouseholdIncome; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Mendaftarkan akun pengguna baru.
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'email' => 'required|string|email|max:190|unique:users',
            'password' => 'required|string|min:8',
            'current_income_idr' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 2. Buat Pengguna Baru (PERBAIKAN: Hash password)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'current_income_idr' => $request->current_income_idr,
        ]);

        if ($request->filled('current_income_idr')) {
            HouseholdIncome::create([
                'user_id' => $user->id,
                'monthly_income_idr' => $request->current_income_idr,
                'effective_from' => Carbon::now(), 
            ]);
        }

        // 3. Buat Token API
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan Respons
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * Login pengguna yang sudah ada.
     */
    public function login(Request $request)
    {
        // Validasi input
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}