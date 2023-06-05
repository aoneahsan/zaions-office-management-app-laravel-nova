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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->float('perSquareFeetPrice')->nullable();
            $table->text('whyInvest')->nullable();
            $table->string('location')->nullable();
            $table->json('coordinates')->nullable();
            $table->string('type')->nullable();
            $table->json('bankDetails')->nullable();
            $table->float('rebatePercentage')->nullable();
            $table->float('totalUnits')->nullable(); // total number of units added by 
            $table->float('remainingUnits')->nullable(); // units which are available for purchase (excluding sold and reserved units)
            $table->float('soldUnits')->nullable(); // successfully sold units
            $table->float('unitsReservedByUsers')->nullable(); // those which users are currently trying to buy
            $table->string('blockName')->nullable();

            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true);
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
        Schema::dropIfExists('projects');
    }
};
