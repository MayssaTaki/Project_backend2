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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');  // Delete course if user/teacher is deleted
            
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('cascade'); // Delete course if category is deleted
                  
            $table->string('name', 200);
            $table->decimal('price', 10, 2);
            $table->string('description', 1000);
            $table->boolean('accepted')->default(false);
            $table->timestamps();
            
            // Optional: Indexes for better performance
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};