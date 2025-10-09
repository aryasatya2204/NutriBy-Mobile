<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AllergyFact;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    /**
     * Mencari fakta alergi berdasarkan kata kunci.
     */
    public function searchFacts(Request $request)
    {
        // 1. Validasi: pastikan ada kata kunci 'query' yang dikirim
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');

        // 2. Lakukan pencarian di database
        $facts = AllergyFact::with('allergenGroup:id,name,image_url') // Ambil juga data dari relasi
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('symptoms', 'LIKE', "%{$query}%")
            ->orWhere('triggers', 'LIKE', "%{$query}%")
            ->get();

        // 3. Kembalikan hasil pencarian
        return response()->json($facts);
    }
}