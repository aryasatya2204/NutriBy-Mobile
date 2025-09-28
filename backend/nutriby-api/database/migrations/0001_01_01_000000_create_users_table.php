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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
            $table->string('name', 120);
            $table->string('email', 190)->unique();
            $table->string('password_hash', 255)->nullable()->comment('NULL if login via OAuth only');
            $table->timestamp('email_verified_at')->nullable();
            $table->decimal('current_income_idr', 12, 2)->nullable();
            // Kolom 'password' bawaan Laravel tidak kita pakai, ganti dengan password_hash
            // $table->rememberToken(); // Ini juga tidak ada di skema SQL Anda
            $table->timestamps(); // created_at dan updated_at
        });

        // Tabel ini biasanya ada di file terpisah, tapi kita bisa gabungkan
        // dengan file bawaan password_resets jika mau, atau biarkan terpisah.
        // Untuk sekarang, biarkan file password_resets yang sudah kita buat.

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};