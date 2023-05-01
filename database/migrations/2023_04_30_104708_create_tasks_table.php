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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId');
            $table->string('type');
            $table->string('namazOffered')->nullable();
            $table->dateTime('namazOfferedAt')->nullable();  // this will be automatic when user enters this data
            $table->string('screenShot')->nullable();
            $table->unsignedBigInteger('verifiedBy')->nullable();
            $table->string('verifierRemarks')->nullable();
            $table->unsignedBigInteger('approvedBy')->nullable();
            $table->string('approverRemarks')->nullable();
            $table->string('weekOfYear')->nullable();
            $table->dateTime('courseStartDate')->nullable();
            $table->dateTime('courseEstimateDate')->nullable();
            $table->integer('courseTotalTimeInHours')->default(0)->nullable();
            $table->integer('perDayCourseContentTimeInHours')->default(0)->nullable();
            $table->integer('numberOfDaysAllowedForCourse')->default(0)->nullable();
            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true);

            $table->schemalessAttributes('extraAttributes');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
