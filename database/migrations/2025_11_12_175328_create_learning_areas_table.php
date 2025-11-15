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
        Schema::create('learning_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 
            $table->string('short')->nullable();    
            $table->string('code')->nullable();    
            $table->foreignId('curriculum_id')   
                ->constrained('curricula')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_areas');
    }
};
