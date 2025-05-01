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
        Schema::create('restrictions', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to the `forms` table (class)
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onDelete('cascade'); // Cascade deletes if the form is deleted

            // Foreign key to the `subjects` table (subject)
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade'); // Cascade deletes if the subject is deleted

            // Restriction type (e.g., max_lessons_per_week, max_remedials)
            $table->string('restriction_type');

            // Maximum value for the restriction (e.g., 2 for max lessons)
            $table->integer('max_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restrictions');
    }
};
