<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->tinyInteger('age_min_month');
            $table->tinyInteger('age_max_month');
            $table->enum('texture', ['puree', 'mash', 'finger', 'family']);
            $table->text('instructions')->nullable();
            $table->json('safety_flags')->nullable();
            $table->decimal('estimated_cost_avg', 10, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['age_min_month', 'age_max_month']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
