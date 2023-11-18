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
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->float('discountPrice')->nullable();
            $table->string('duration')->nullable();
            $table->string('category')->nullable();
            $table->string('tags')->nullable();
            $table->string('url')->nullable();
            $table->string('discountPercentage')->nullable();

            $table->json('extraAttributes')->nullable();
            $table->softDeletes();

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
