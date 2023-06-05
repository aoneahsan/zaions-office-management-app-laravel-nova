<?php

use App\Zaions\Enums\FPI\Projects\ProjectMeasuringUnitTypeEnum;
use App\Zaions\Enums\FPI\Projects\ProjectTypeEnum;
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
            $table->float('perUnitPrice')->nullable()->default(0);
            $table->string('unitMeasuredIn')->nullable()->default(ProjectMeasuringUnitTypeEnum::squareFeet->name); // as the units measuring unit is "square feet" so the "perUnitPrice" is actually price per square feet.
            $table->text('whyInvest')->nullable();
            $table->string('location')->nullable();
            $table->json('coordinates')->nullable();
            $table->string('type')->nullable()->default(ProjectTypeEnum::other->name);
            $table->json('bankDetails')->nullable();
            $table->float('rebatePercentage')->nullable()->default(0);
            $table->float('totalUnits')->nullable()->default(0); // total number of units added by 
            $table->float('remainingUnits')->nullable()->default(0); // units which are available for purchase (excluding sold and reserved units)
            $table->float('soldUnits')->nullable()->default(0); // successfully sold units
            $table->float('unitsReservedByUsers')->nullable()->default(0); // those which users are currently trying to buy
            $table->string('blockName')->nullable();

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
        Schema::dropIfExists('projects');
    }
};
