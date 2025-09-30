<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('menu_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('restrict');
            $table->decimal('quantity', 8, 2);
            $table->string('unit', 16);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['menu_id', 'ingredient_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('menu_ingredients');
    }
};
