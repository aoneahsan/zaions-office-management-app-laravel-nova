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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // new fields
            $table->string('slug')->nullable();
            $table->string('profilePitcher')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('timezone')->nullable();
            $table->string('address')->nullable();
            $table->string('cnic')->nullable();
            $table->string('referralCode')->nullable();

            $table->boolean('isActive')->default(true)->nullable();
            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->json('extraAttributes')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
