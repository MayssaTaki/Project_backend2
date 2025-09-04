<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teachers_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers', 'user_id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students', 'user_id')->cascadeOnDelete();
            $table->tinyInteger('evaluation_value')->unsigned()->checkBetween(0, 5); // 0-5 rating
            $table->timestamps();
            
            // Prevent duplicate evaluations from same student to same teacher
            $table->unique(['teacher_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers_evaluations');
    }
};