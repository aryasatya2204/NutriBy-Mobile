<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_key', 64)->unique();
            $table->decimal('waz_min', 4, 2)->nullable();
            $table->decimal('waz_max', 4, 2)->nullable();
            $table->decimal('haz_min', 4, 2)->nullable();
            $table->decimal('haz_max', 4, 2)->nullable();
            $table->decimal('wlz_min', 4, 2)->nullable();
            $table->decimal('wlz_max', 4, 2)->nullable();
            $table->enum('composite_status_is', ['normal','underweight','stunted','wasted','overweight'])->nullable();
            $table->boolean('requires_iron_focus')->nullable();
            $table->boolean('requires_energy_focus')->nullable();
            $table->boolean('requires_omega3_focus')->nullable();
            $table->integer('priority')->default(0);
            $table->text('message_template');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_rules');
    }
};