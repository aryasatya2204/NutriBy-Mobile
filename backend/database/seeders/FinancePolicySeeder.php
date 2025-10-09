<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FinancePolicy;

class FinancePolicySeeder extends Seeder
{
    public function run(): void
    {
        // Buat kebijakan default 'v1'
        FinancePolicy::updateOrCreate(
            ['calc_version' => 'v1'],
            [
                'food_share_pct' => 0.450, // 45% dari gaji untuk makanan
                'mpasi_share_pct_per_child' => 0.180, // 18% dari dana makanan untuk MPASI per anak
            ]
        );
    }
}