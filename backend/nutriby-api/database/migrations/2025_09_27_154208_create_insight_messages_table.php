<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insight_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('growth_assessment_id')->constrained('growth_assessments')->onDelete('cascade');
            $table->foreignId('message_rule_id')->nullable()->constrained('message_rules')->onDelete('set null');
            $table->text('message');
            $table->timestamp('generated_at')->useCurrent();

            $table->index(['child_id', 'generated_at'], 'idx_im_child_gen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insight_messages');
    }
};