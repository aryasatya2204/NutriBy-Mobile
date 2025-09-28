<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->enum('preferable_type', ['menu', 'ingredient']);
            $table->unsignedBigInteger('preferable_id');
            $table->tinyInteger('like_dislike'); // 1..5
            $table->timestamp('created_at')->useCurrent();

            $table->index(['preferable_type', 'preferable_id'], 'idx_cp_poly');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_preferences');
    }
};