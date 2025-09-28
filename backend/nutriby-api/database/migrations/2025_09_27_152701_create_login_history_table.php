<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('provider_id');
            $table->dateTime('logged_in_at')->useCurrent();
            $table->binary('ip_addr', 16)->nullable();
            $table->binary('ua_hash', 64)->nullable();
            $table->foreign('provider_id')->references('id')->on('oauth_providers')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
