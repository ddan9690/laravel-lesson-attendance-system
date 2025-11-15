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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->integer('adm')->unique();
            $table->string('phone')->nullable();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('grade_stream_id')->constrained('grade_streams')->cascadeOnDelete();
            $table->enum('status', ['active', 'transferred', 'graduated'])->default('active');
            $table->foreignId('joined_academic_year_id')->constrained('academic_years');
            $table->foreignId('joined_term_id')->constrained('terms');
            $table->foreignId('left_academic_year_id')->nullable()->constrained('academic_years');
            $table->foreignId('left_term_id')->nullable()->constrained('terms');
            $table->year('graduation_year')->nullable();
            $table->foreignId('added_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
