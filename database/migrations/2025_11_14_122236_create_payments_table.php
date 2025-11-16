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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('grade_stream_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('student_name');
            $table->integer('student_adm');
            $table->string('grade_name')->nullable();
            $table->string('stream_name')->nullable();
            
             $table->integer('amount')->change();
            $table->enum('payment_type', ['cash', 'mpesa'])->default('mpesa');
            $table->string('mpesa_transaction_number')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
