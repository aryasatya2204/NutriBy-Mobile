<?php

namespace App\Services;

use App\Models\User;
use App\Models\FinancePolicy;

class BudgetCalculationService
{
    /**
     * Menghitung rekomendasi budget MPASI bulanan berdasarkan pendapatan.
     *
     * @param User $user Pengguna yang akan dihitung budgetnya.
     * @return float|null Rekomendasi budget atau null jika tidak bisa dihitung.
     */
    public function calculate(User $user): ?float
    {
        // 1. Ambil kebijakan keuangan yang aktif saat ini (versi terbaru)
        $policy = FinancePolicy::orderBy('calc_version', 'desc')->first();

        // Jika tidak ada kebijakan atau tidak ada pendapatan, kita tidak bisa menghitung
        if (!$policy || is_null($user->current_income_idr)) {
            return null;
        }

        // 2. Ambil data yang relevan dari user
        $monthlyIncome = $user->current_income_idr;
        // Hitung jumlah anak di bawah 2 tahun (ini bisa disempurnakan)
        $childrenUnderTwo = $user->children()->where('birth_date', '>', now()->subYears(2))->count();

        // Jika tidak ada anak di bawah 2 tahun, tidak perlu budget MPASI
        if ($childrenUnderTwo === 0) {
            return 0.0;
        }

        // 3. Lakukan kalkulasi sesuai formula
        $suggestedBudget = $monthlyIncome
                         * $policy->food_share_pct
                         * $policy->mpasi_share_pct_per_child
                         * $childrenUnderTwo;

        // 4. (Opsional) Terapkan batas atas/bawah jika ada
        if (!is_null($policy->upper_cap_idr) && $suggestedBudget > $policy->upper_cap_idr) {
            $suggestedBudget = $policy->upper_cap_idr;
        }

        if (!is_null($policy->lower_cap_idr) && $suggestedBudget < $policy->lower_cap_idr) {
            $suggestedBudget = $policy->lower_cap_idr;
        }

        return round($suggestedBudget, 2);
    }
}