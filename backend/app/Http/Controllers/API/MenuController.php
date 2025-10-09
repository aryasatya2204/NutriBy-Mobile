<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function search(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'available_ingredients' => 'nullable|array',
            'available_ingredients.*' => 'integer|exists:ingredients,id',
        ]);

        $child = Child::find($validated['child_id']);

        // Keamanan: Pastikan user hanya bisa mencari untuk anaknya sendiri
        if ($child->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // 1. Ambil data dasar dari profil anak
        $ageInMonths = Carbon::parse($child->birth_date)->diffInMonths(now());
        $childAllergenIds = $child->allergies()->pluck('allergen_group_id');
        $childPreferenceIds = $child->preferences()->where('preferable_type', 'menu')->pluck('preferable_id');

        // 2. Mulai membangun query
        $query = Menu::query();

        // 3. Filter Wajib: Berdasarkan Usia Anak
        $query->where('age_min_month', '<=', $ageInMonths)
              ->where('age_max_month', '>=', $ageInMonths);

        // 4. Filter Wajib: Hindari Alergi
        if ($childAllergenIds->isNotEmpty()) {
            $query->whereDoesntHave('ingredients.allergenGroups', function ($q) use ($childAllergenIds) {
                $q->whereIn('allergen_group_id', $childAllergenIds);
            });
        }

        // 5. Filter Opsional: Berdasarkan Bahan yang Tersedia
        if (!empty($validated['available_ingredients'])) {
            $ingredientIds = $validated['available_ingredients'];
            $query->whereHas('ingredients', function ($q) use ($ingredientIds) {
                $q->whereIn('ingredients.id', $ingredientIds);
            });
        }

        // 6. Ambil semua menu yang cocok
        $menus = $query->get();

        // 7. Proses Scoring untuk Prioritas (Makanan Kesukaan)
        $sortedMenus = $menus->sortByDesc(function ($menu) use ($childPreferenceIds) {
            return $childPreferenceIds->contains($menu->id) ? 1 : 0;
        });

        return response()->json($sortedMenus->values());
    }
}