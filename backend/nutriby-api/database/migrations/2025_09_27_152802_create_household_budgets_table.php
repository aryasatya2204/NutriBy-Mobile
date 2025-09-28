<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('household_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('income_id')->nullable()->constrained('household_incomes')->onDelete('set null');
            $table->enum('method', ['derived', 'manual'])->default('derived');
            $table->decimal('suggested_monthly_mpasi_budget_idr', 12, 2)->nullable();
            $table->decimal('committed_monthly_mpasi_budget_idr', 12, 2);
            $table->string('calc_version', 16)->nullable();
            $table->dateTime('effective_from');
            $table->json('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('household_budgets');
    }
};