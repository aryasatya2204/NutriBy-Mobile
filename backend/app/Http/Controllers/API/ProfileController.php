<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HouseholdBudget;
use App\Models\HouseholdIncome;
use Illuminate\Http\Request;
use App\Services\BudgetCalculationService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileController extends Controller
{

    protected $budgetService;

    // Suntikkan service melalui constructor
    public function __construct(BudgetCalculationService $budgetService)
    {
        $this->budgetService = $budgetService;
    }
    /**
     * Menampilkan profil user yang sedang login.
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Mengupdate profil user yang sedang login.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|required|string|max:120',
        ]);

        $user->update($request->all());

        return response()->json($user);
    }

    public function updateIncome(Request $request)
    {
        $validated = $request->validate([
            'monthly_income_idr' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        // 1. Update pendapatan di tabel users (sebagai cache)
        $user->update([
            'current_income_idr' => $validated['monthly_income_idr']
        ]);

        // 2. Buat catatan riwayat pendapatan baru
        $incomeRecord = HouseholdIncome::create([
            'user_id' => $user->id,
            'monthly_income_idr' => $validated['monthly_income_idr'],
            'effective_from' => Carbon::now(),
        ]);

        // 3. Panggil Service untuk menghitung rekomendasi budget
        $suggestedBudget = $this->budgetService->calculate($user);

        // 4. Simpan atau perbarui data budget
        $budgetRecord = HouseholdBudget::updateOrCreate(
            ['user_id' => $user->id, 'method' => 'derived'], // Cari budget 'derived' milik user
            [
                'income_id' => $incomeRecord->id,
                'suggested_monthly_mpasi_budget_idr' => $suggestedBudget,
                // Set committed_budget sama dengan suggestion sebagai default awal
                'committed_monthly_mpasi_budget_idr' => $suggestedBudget ?? 0,
                'effective_from' => Carbon::now(),
            ]
        );

        return response()->json([
            'message' => 'Income updated and budget calculated successfully.',
            'new_budget' => $budgetRecord
        ]);
    }
}