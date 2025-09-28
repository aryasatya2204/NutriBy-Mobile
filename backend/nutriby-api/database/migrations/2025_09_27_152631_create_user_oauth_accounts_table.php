<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_oauth_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('provider_id');
            $table->string('provider_user_id', 191);
            $table->string('email_at_provider', 190)->nullable();
            $table->binary('access_token_hash', 512)->nullable();
            $table->binary('refresh_token_hash', 512)->nullable();
            $table->dateTime('token_expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('provider_id')->references('id')->on('oauth_providers')->onDelete('cascade');

            $table->unique(['user_id', 'provider_id']);
            $table->unique(['provider_id', 'provider_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_oauth_accounts');
    }
};
