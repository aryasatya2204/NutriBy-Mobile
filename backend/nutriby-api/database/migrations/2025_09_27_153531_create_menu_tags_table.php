<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id')->primary();
            $table->boolean('iron_dense')->default(false);
            $table->boolean('energy_dense')->default(false);
            $table->boolean('protein_high')->default(false);
            $table->boolean('omega3_source')->default(false);
            $table->boolean('finger_food')->default(false);
            $table->boolean('allergen_egg')->default(false);
            $table->boolean('allergen_milk')->default(false);
            $table->boolean('allergen_fish')->default(false);
            $table->boolean('allergen_peanut')->default(false);
            $table->boolean('allergen_treenuts')->default(false);
            $table->boolean('allergen_soy')->default(false);
            $table->boolean('allergen_wheat')->default(false);
            $table->boolean('allergen_sesame')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_tags');
    }
};