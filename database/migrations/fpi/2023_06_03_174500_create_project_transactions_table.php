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
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('projectId');
            $table->unsignedBigInteger('sellerUserId'); // mainly developer Id, but any one who was owner of that project/property
            $table->unsignedBigInteger('buyerUserId'); // broker/investor anyone who is buying the project/property

            $table->float('unitsBeforeTransaction')->nullable();
            $table->float('unitsAfterTransaction')->nullable();
            $table->float('unitsBoughtInTransaction')->nullable();
            $table->string('unitsSerialNumberStartsFrom')->nullable(); // i will only finalize/fill this field on successful purchase
            $table->string('unitsSerialNumberEndsAt')->nullable(); // i will only finalize/fill this field on successful purchase
            $table->string('perUnitPrice')->nullable();
            $table->string('unitMeasuredIn')->nullable()->default(ProjectMeasuringUnitTypeEnum::squareFeet->name); // as the units measuring unit is "square feet" so the "perUnitPrice" is actually price per square feet.
            $table->string('status')->nullable()->default(ProjectTransactionStatusEnum::initiated->name); // when the transaction is created by any user and no other status is selected
            $table->string('transactionType')->nullable()->default(ProjectTransactionTypeEnum::purchase->name);

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
        Schema::dropIfExists('project_transactions');
    }
};
