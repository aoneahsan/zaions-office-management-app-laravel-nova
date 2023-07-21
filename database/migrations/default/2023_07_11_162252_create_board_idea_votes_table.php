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
        Schema::create('board_idea_votes', function (Blueprint $table) {
            $table->id();
            // $table->primary(['userId', 'boardIdeaId']);

            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('projectId');
            $table->unsignedBigInteger('boardId');
            $table->unsignedBigInteger('boardIdeaId');

            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('boardIdeaId')->references('id')->on('board_ideas')->onDelete('cascade');
            $table->foreign('boardId')->references('id')->on('boards')->onDelete('cascade');
            $table->foreign('projectId')->references('id')->on('projects')->onDelete('cascade');


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_idea_votes');
    }
};
