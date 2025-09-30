<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ChildController extends Controller
{
    /**
     * Menampilkan daftar anak milik pengguna yang sedang login.
     */
    public function index()
    {
        $children = Auth::user()->children;
        return response()->json($children);
    }

    /**
     * Menyimpan data anak baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'sex' => 'required|in:F,M',
            'birth_date' => 'required|date',
            'initial_weight_kg' => 'required|numeric|min:0',
            'initial_length_cm' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 1. Buat data anak dan hubungkan dengan user yang login
        $child = Auth::user()->children()->create([
            'name' => $request->name,
            'sex' => $request->sex,
            'birth_date' => $request->birth_date,
        ]);

        // 2. Buat catatan pertumbuhan pertama untuk anak ini
        $child->growthRecords()->create([
            'measured_at' => Carbon::now(),
            'weight_kg' => $request->initial_weight_kg,
            'length_cm' => $request->initial_length_cm,
        ]);

        return response()->json($child, 201);
    }

    /**
     * Menampilkan detail satu anak spesifik.
     */
    public function show(Child $child)
    {
        // Pengecekan Keamanan: Pastikan user hanya bisa melihat anaknya sendiri
        if ($child->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($child);
    }

    /**
     * Mengupdate data anak.
     */
    public function update(Request $request, Child $child)
    {
        // Pengecekan Keamanan
        if ($child->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:120',
            // Data lain yang boleh diupdate, contoh: 'birth_date' => 'sometimes|required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $child->update($request->all());

        return response()->json($child);
    }

    /**
     * Menghapus data anak.
     */
    public function destroy(Child $child)
    {
        // Pengecekan Keamanan
        if ($child->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $child->delete();

        return response()->json(null, 204);
    }
}