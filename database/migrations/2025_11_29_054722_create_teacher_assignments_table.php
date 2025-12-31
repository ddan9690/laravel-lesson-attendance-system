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
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('learning_area_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('form_id')->nullable()->constrained()->nullOnDelete();  
            $table->foreignId('grade_id')->nullable()->constrained()->nullOnDelete();  

            // Stream
            $table->foreignId('form_stream_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('grade_stream_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->unique([
                'teacher_id',
                'subject_id',
                'learning_area_id',
                'form_id',
                'grade_id',
                'form_stream_id',
                'grade_stream_id',
            ], 'unique_teacher_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
