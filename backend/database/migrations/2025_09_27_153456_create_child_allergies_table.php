<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->unsignedTinyInteger('allergen_group_id');

            $table->foreign('allergen_group_id')->references('id')->on('allergen_groups')->onDelete('cascade');
            $table->unique(['child_id', 'allergen_group_id'], 'uq_child_allergy');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_allergies');
    }
};