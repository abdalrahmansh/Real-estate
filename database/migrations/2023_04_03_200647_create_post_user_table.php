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
        Schema::create('post_user', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('operation_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('price')->nullable();
            $table->string('duration')->nullable();
            $table->string('description')->nullable();
            $table->morphs('postsable');
            $table->integer('is_accepted')->default(0);
            $table->timestamps();

            // $table->foreign('post_id')
            //     ->references('id')
            //     ->on('posts')      
            //     ->onDelete('cascade')
            //     ->onUpdate('cascade');
            
            $table->foreign('operation_id')
                ->references('id')
                ->on('operations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_user');
    }
};
