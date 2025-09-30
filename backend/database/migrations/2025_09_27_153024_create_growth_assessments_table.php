<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // dalam file create_growth_assessments_table.php
    public function up(): void
    {
        Schema::create('growth_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('growth_record_id')->unique()->constrained('growth_records')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->dateTime('assessed_at');
            $table->decimal('z_waz', 4, 2);
            $table->decimal('z_haz', 4, 2);
            $table->decimal('z_wlz', 4, 2);
            $table->enum('status_waz', ['low', 'normal', 'high']);
            $table->enum('status_haz', ['low', 'normal', 'high']);
            $table->enum('status_wlz', ['low', 'normal', 'high']);
            $table->enum('composite_status', ['normal', 'underweight', 'stunted', 'wasted', 'overweight']);
            $table->json('needs_flags_json')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('growth_assessments');
    }
};
