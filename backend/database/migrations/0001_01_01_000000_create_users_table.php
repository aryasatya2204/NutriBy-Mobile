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
        $table->id(); 
        $table->string('name', 120);
        $table->string('email', 190)->unique();
        $table->string('password', 255)->nullable()->comment('Boleh NULL jika login hanya via OAuth');
        $table->timestamp('email_verified_at')->nullable();
        $table->decimal('current_income_idr', 12, 2)->nullable();
        $table->rememberToken(); 
        $table->timestamps(); 
    });

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