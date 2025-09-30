<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('category', 64)->nullable();
            $table->string('unit_default', 16);
            $table->decimal('average_price', 10, 2)->nullable();
            $table->tinyInteger('edible_portion_pct')->nullable();
            $table->tinyInteger('choking_min_age_month')->nullable();
            $table->boolean('is_halal')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
