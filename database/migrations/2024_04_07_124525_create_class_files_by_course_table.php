<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('class_files_by_course', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('class_id');
      $table->string('file_name');
      $table->string('size', 50);
      $table->string('time', 50)->nullable();
      $table->string('resolution', 20)->nullable();
      $table->string('file');
      $table->string('type', 20);
      $table->foreign('class_id')->references('id')->on('course_classes');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('class_files_by_course');
  }
};
