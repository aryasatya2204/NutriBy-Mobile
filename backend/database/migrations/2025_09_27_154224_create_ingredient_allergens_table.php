<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_allergens', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredient_id');
            $table->unsignedTinyInteger('allergen_group_id');

            $table->primary(['ingredient_id', 'allergen_group_id']);

            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->foreign('allergen_group_id')->references('id')->on('allergen_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_allergens');
    }
};