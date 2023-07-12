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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('projectId');

            $table->string('title')->nullable();


            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();

            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('projectId')->references('id')->on('projects');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
