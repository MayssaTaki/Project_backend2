<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('video_url');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->bigInteger('file_size'); // in bytes
            $table->string('video_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_videos');
    }
};