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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');

            $table->string('attachmentPath')->nullable();
            $table->string('attachmentName')->nullable();
            $table->string('attachmentSize')->nullable();
            $table->morphs('attachable');
            $table->text('attachmentInfo')->nullable();

            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
