<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergen_groups', function (Blueprint $table) {
            $table->tinyIncrements('id'); 
            $table->string('code', 32)->unique();
            $table->string('name', 64);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergen_groups');
    }
};