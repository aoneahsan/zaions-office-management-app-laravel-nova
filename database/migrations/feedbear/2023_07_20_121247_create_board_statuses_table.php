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
        Schema::create('board_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('boardId');

            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->boolean('isDefault')->nullable();
            $table->boolean('isEditable')->nullable();
            $table->boolean('isDeletable')->nullable();

            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('boardId')->references('id')->on('boards')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_statuses');
    }
};
