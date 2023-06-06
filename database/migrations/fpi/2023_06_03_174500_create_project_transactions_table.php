<?php

use App\Zaions\Enums\FPI\Projects\ProjectMeasuringUnitTypeEnum;
use App\Zaions\Enums\FPI\Projects\ProjectTransactionStatusEnum;
use App\Zaions\Enums\FPI\Projects\ProjectTransactionTypeEnum;
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
        Schema::create('project_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueId')->nullable();
            $table->unsignedBigInteger('userId'); // keep this to user from whom the purchase request is created for 
            $table->unsignedBigInteger('projectId');
            $table->unsignedBigInteger('sellerId'); // mainly developer Id, but any one who was owner of that project/property
            $table->unsignedBigInteger('creatorId'); // brokerId in case if broker creates this transaction request for investor

            $table->string('referralCode')->nullable(); // broker User Referral code, if broker creates this transaction request for investor user, when broker will create a transaction for a investor the status of the transaction will be "initiated", from there, if investor accepts it, it will go to "pendingForPayment", otherwise will be "cancelled"
            $table->float('unitsBeforeTransaction')->nullable()->default(0);
            $table->float('unitsAfterTransaction')->nullable()->default(0);
            $table->float('unitsBoughtInTransaction')->nullable()->default(0);
            // serial number consist of - date(current date) + block initial + project name initial + serial count number (will start from 1 up to the total number of units)
            $table->string('unitsSerialNumberStartsFrom')->nullable(); // i will only finalize/fill this field on successful purchase
            $table->string('unitsSerialNumberEndsAt')->nullable(); // i will only finalize/fill this field on successful purchase
            $table->float('perUnitPrice')->nullable()->default(0);
            $table->string('unitMeasuredIn')->nullable()->default(ProjectMeasuringUnitTypeEnum::squareFeet->name); // as the units measuring unit is "square feet" so the "perUnitPrice" is actually price per square feet.
            $table->string('status')->nullable()->default(ProjectTransactionStatusEnum::initiated->name); // when the transaction is created by any user and no other status is selected
            $table->string('transactionType')->nullable()->default(ProjectTransactionTypeEnum::purchase->name);

            $table->integer('sortOrderNo')->default(0)->nullable();
            $table->boolean('isActive')->default(true)->nullable();
            $table->json('extraAttributes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('projectId')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_transactions');
    }
};
