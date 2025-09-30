<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mpasi_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->enum('plan_type', ['weekly', 'custom'])->default('weekly');
            $table->date('week_start_date')->nullable();
            $table->decimal('total_estimated_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->integer('version')->default(1);
            $table->enum('generated_by', ['engine', 'user'])->default('engine');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['child_id', 'week_start_date'], 'idx_pl_child_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mpasi_plans');
    }
};