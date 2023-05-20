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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('floor')->nullable();
            $table->string('space')->nullable();
            $table->string('direction')->nullable();
            $table->string('description')->nullable();
            $table->integer('no_rooms')->nullable();
            $table->timestamps();
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_new')->nullable();
            $table->string('year')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('space');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('lands');
    }
};
