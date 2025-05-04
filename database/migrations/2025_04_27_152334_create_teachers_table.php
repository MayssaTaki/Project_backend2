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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('first_name');
    $table->string('last_name');
    $table->string('specialization');
    $table->text('Previous_experiences')->nullable();
    $table->string('phone')->nullable();
    $table->string('profile_image')->nullable();
    $table->string('country')->nullable();
    $table->string('city')->nullable();
    $table->enum('gender', ['male', 'female']);

    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
