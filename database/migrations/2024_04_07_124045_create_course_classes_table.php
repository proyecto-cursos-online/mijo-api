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
    Schema::create('course_classes', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('section_id');
      $table->string('vimeo_id')->nullable();
      $table->string('name', 50);
      $table->string('time', 50)->nullable();
      $table->tinyInteger('state');
      $table->foreign('section_id')->references('id')->on('course_sections');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('course_classes');
  }
};
