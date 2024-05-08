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
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('vimeo_id', 50)->nullable();
            $table->string('title')->nullable();
            $table->text('slug');
            $table->text('subtitle');
            $table->string('level', 120);
            $table->string('language', 150);
            $table->string('time', 50);
            $table->longText('description');
            $table->longText('requirements');
            $table->longText('participant'); //who is it for
            $table->float('price_in_dollar')->default(0); //who is it for
            $table->float('price_in_soles')->default(0); //who is it for
            $table->tinyInteger('state');
            $table->string('backgroud_image')->nullable();
            $table->foreign('instructor_id')->references('id')->on('instructors');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('categories');
            $table->timestamps();
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
