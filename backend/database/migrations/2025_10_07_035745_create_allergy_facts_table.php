<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('allergy_facts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('allergen_group_id');
            $table->string('title', 160);
            $table->text('content');
            $table->text('symptoms')->nullable();
            $table->text('triggers')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('allergen_group_id')->references('id')->on('allergen_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_facts');
    }
};