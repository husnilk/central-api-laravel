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
        Schema::disableForeignKeyConstraints();

        Schema::create('study_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained()->cascadeOnUpdate();
            $table->foreignUuid('periode_id')->constrained('periods')->cascadeOnUpdate();
            $table->foreignUuid('counselor_id')->constrained('lecturers')->cascadeOnUpdate();
            $table->integer('status')->default(1);
            $table->date('registered_at');
            $table->double('gpa', 8, 2);
            $table->foreignId('period_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_plans');
    }
};
