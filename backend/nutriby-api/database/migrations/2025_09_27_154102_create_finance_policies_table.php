<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_policies', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('calc_version', 16)->unique();
            $table->decimal('food_share_pct', 5, 3)->default(0.450);
            $table->decimal('mpasi_share_pct_per_child', 5, 3)->default(0.180);
            $table->decimal('lower_cap_idr', 12, 2)->nullable();
            $table->decimal('upper_cap_idr', 12, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_policies');
    }
};