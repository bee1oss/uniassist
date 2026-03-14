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
        Schema::create('analysis_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->string('report_type',50);
            $table->string('title');
            $table->json('input_snapshot')->nullable();
            $table->json('metrics_json');
            $table->longText('ai_summary')->nullable();
            $table->longText('recommendations')->nullable();

            $table->index(['department_id','semester_id']);
            $table->index('report_type');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_reports');
    }
};
