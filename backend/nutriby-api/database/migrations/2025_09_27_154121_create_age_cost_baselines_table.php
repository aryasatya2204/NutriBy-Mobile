<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_cost_baselines', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('age_min_month');
            $table->tinyInteger('age_max_month');
            $table->decimal('baseline_monthly_cost_idr', 12, 2);

            $table->unique(['age_min_month', 'age_max_month'], 'uq_age_baseline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_cost_baselines');
    }
};