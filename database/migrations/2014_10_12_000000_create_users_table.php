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
            $table->integer('dailyMinOfficeTime')->default(8)->min(3)->max(12)->nullable();
            $table->integer('dailyMinOfficeTimeActivity')->default(85)->min(75)->max(100)->nullable();
            $table->boolean('isActive')->default(true);
            $table->schemalessAttributes('extraAttributes');

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
