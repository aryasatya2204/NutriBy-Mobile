<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->tinyInteger('age_min_month')->nullable();
            $table->tinyInteger('age_max_month')->nullable();
            $table->decimal('energy_kcal', 6, 1);
            $table->decimal('protein_g', 6, 2);
            $table->decimal('fat_g', 6, 2);
            $table->decimal('carbs_g', 6, 2);
            $table->decimal('iron_mg', 6, 2);
            $table->decimal('zinc_mg', 6, 2);
            $table->decimal('vitamin_a_ugRE', 8, 2);
            $table->decimal('vitamin_c_mg', 6, 2);
            $table->decimal('dha_mg', 8, 2)->nullable();
            $table->boolean('is_estimated')->default(true);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['menu_id', 'age_min_month', 'age_max_month'], 'idx_np_menu_age');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrient_profiles');
    }
};