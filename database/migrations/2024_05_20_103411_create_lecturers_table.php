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

        Schema::create('lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary()->foreign('lecturers.id');
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('nidn')->nullable();
            $table->string('karpeg')->nullable();
            $table->string('npwp')->nullable();
            $table->enum('gender', ["M","F"])->nullable();
            $table->date('birthday')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->foreignUuid('department_id')->constrained();
            $table->string('photo')->nullable();
            $table->integer('marital_status')->nullable();
            $table->integer('religion')->nullable();
            $table->integer('association_type')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
