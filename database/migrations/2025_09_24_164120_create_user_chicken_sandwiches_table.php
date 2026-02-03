<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{   
    /**
    * Run migrations
    */
    public function up()
    {
        Schema::create('user_chicken_sandwiches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chicken_sandwich_id');
            $table->integer('score');
            $table->text('review')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chicken_sandwich_id')->references('id')->on('chicken_sandwiches')->onDelete('cascade');

            $table->unique(['user_id', 'chicken_sandwich_id']);
        });
    }

    /**
    * Reverse migrations
    */
    public function down()
    {
        Schema::dropIfExists('user_chicken_sandwiches');
    }
};
