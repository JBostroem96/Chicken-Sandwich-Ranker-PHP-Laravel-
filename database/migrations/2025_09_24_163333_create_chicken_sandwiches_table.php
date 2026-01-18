<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    /**
    * Run migrations
    */
    public function up(): void
    {
        Schema::create('chicken_sandwiches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('company', 255); 
            $table->text('description')->nullable();
            $table->string('image')->nullable(); 
            $table->string('logo')->nullable();
            $table->unsignedInteger('entries')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->float('average_score', 3, 2)->default(0); // e.g. 4.25
            $table->timestamps();
        });

    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('chicken_sandwiches');
    }
};
