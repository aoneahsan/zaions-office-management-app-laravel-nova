<?php

namespace App\Nova\Actions\FPI\Projects;

use App\Models\FPI\Project;
use App\Models\FPI\ProjectTransaction;
use App\Models\User;
use App\Zaions\Enums\FPI\Projects\ProjectTransactionStatusEnum;
use App\Zaions\Enums\FPI\Projects\ProjectTransactionTypeEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Str;

class ProjectUnitsPurchaseAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = "Purchase Units";
    public $showInline = true;

    public $confirmButtonText = 'Submit';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $currentUser = Auth::user();
        $currentUserId = $currentUser->id;

        $units = floatval($fields->units);
        $buyingForYourself = $fields->buyingForYourself;
        $investorPhoneNumber = $fields->investorPhoneNumber;
        $investorUserId = null;
        if (!$buyingForYourself && $investorPhoneNumber && strlen($investorPhoneNumber) == 11) {
            $investorUser = User::role(RolesEnum::investor->name)->where('phoneNumber', $investorPhoneNumber)->first();
            if ($investorUser) {
                $investorUserId = $investorUser->id;
            } else {
                return Action::danger('Error Occurred, no investor found with provided phone number!');
            }
        }

        // $requestedUnitsExceedAvailableUnits = false;
        // foreach ($models as $model) {
        //     if ($model->remainingUnits < $units) {
        //         $requestedUnitsExceedAvailableUnits = true;
        //         break;
        //     }
        // }

        // if ($requestedUnitsExceedAvailableUnits) {
        //     Action::danger('One or more projects have less remaining units then what you want to buy, Please try again without these projects.');
        // } else {
        //     foreach ($models as $model) {

        //     }
        // }

        if (count($models) > 1) {
            return Action::danger('Please run purchase action on one project at a time.');
        }

        $requestCompletedSuccessfully = false;
        foreach ($models as $model) {
            $projectRemainingUnits = floatval($model->remainingUnits);
            if ($projectRemainingUnits >= $units) {
                // Data for Project Transaction Table New Entry
                $uniqueId = uniqid();
                $userId = $currentUserId;
                $projectId = $model->id;
                $sellerId = $model->userId;
                $buyerId = $buyingForYourself ? $currentUserId : $investorUserId;
                if (!$investorUserId && !$buyingForYourself && $investorPhoneNumber && strlen($investorPhoneNumber) === 11) {
                    $investorUser = User::where('phoneNumber', $investorPhoneNumber)->first();
                    if ($investorUser && $investorUser->id) {
                        $buyerId = $investorUser->id;
                    }
                }
                $referralCode = $fields->referralCode;
                if (!$buyingForYourself && ZHelpers::isBroker($currentUser) && !$referralCode) {
                    $referralCode = $currentUser->referralCode;
                }
                $unitsBeforeTransaction = $projectRemainingUnits;
                $unitsAfterTransaction = $projectRemainingUnits - $units;
                $unitsBoughtInTransaction = $units;
                // i will assign serial number when this transaction gets marked as "approved".
                // $projectSerialNumberData = $this->getProjectSerialNumber($model, $unitsBoughtInTransaction);
                // $unitsSerialNumberStartsFrom = $projectSerialNumberData['startsFrom'];
                // $unitsSerialNumberEndsAt = $projectSerialNumberData['endsAt'];
                $perUnitPrice = $model->perUnitPrice;
                $unitMeasuredIn = $model->unitMeasuredIn;
                $status = $buyingForYourself ? ProjectTransactionStatusEnum::pendingForPayment->name : ProjectTransactionStatusEnum::initiated->name;
                $transactionType = ProjectTransactionTypeEnum::purchase->name;

                // Create Project Transaction
                $requestCompletedSuccessfully = ProjectTransaction::create([
                    'uniqueId' => $uniqueId,
                    'userId' => $userId,
                    'projectId' => $projectId,
                    'sellerId' => $sellerId,
                    'buyerId' => $buyerId,
                    'referralCode' => $referralCode,
                    'unitsBeforeTransaction' => $unitsBeforeTransaction,
                    'unitsAfterTransaction' => $unitsAfterTransaction,
                    'unitsBoughtInTransaction' => $unitsBoughtInTransaction,
                    'perUnitPrice' => $perUnitPrice,
                    'unitMeasuredIn' => $unitMeasuredIn,
                    'status' => $status,
                    'transactionType' => $transactionType
                ]);

                if ($requestCompletedSuccessfully) {
                    // Update the Project Model with the updated info
                    $item = Project::where('id', $model->id)->first();
                    if ($item) {
                        $remainingUnits = floatval($model->remainingUnits);
                        $updatedRemainingUnits = $remainingUnits - $units;
                        $unitsReservedByUsers = floatval($model->unitsReservedByUsers);
                        $updatedUnitsReservedByUsers = $unitsReservedByUsers + $units;

                        // update the record
                        $requestCompletedSuccessfully = $item->update([
                            'remainingUnits' => $updatedRemainingUnits,
                            'unitsReservedByUsers' => $updatedUnitsReservedByUsers
                        ]);
                    }
                }
            } else {
                // only if we are forcing user to run action on single project
                return Action::danger('No more units available for purchase, please try again later!');
            }
        }

        if ($requestCompletedSuccessfully) {
            return Action::message('Request Completed Successfully!');
        } else {
            return Action::danger('Request Failed, Try again!');
        }
    }

    public function getProjectSerialNumber(Project $model, $unitsBoughtInTransaction)
    {
        $currentDate = Carbon::now(ZHelpers::getTimezone())->toDateString();
        $blockNameInitials = ZHelpers::getInitials($model->blockName);
        $projectTitleInitials = ZHelpers::getInitials($model->title);
        $projectUnitsSoldTill = $model->soldUnits;
        $serialNumberStartsFrom = floatval($projectUnitsSoldTill) + 1;
        $serialNumberEndsAt = $serialNumberStartsFrom + $unitsBoughtInTransaction;
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Number of Units to Purchase', 'units'),

            Boolean::make('Buying for yourself', 'buyingForYourself')
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isBroker($request->user());
                })
                ->default(true),

            Number::make('Investor Phone Number', 'investorPhoneNumber')
                ->hide()
                ->dependsOn(['buyingForYourself'], function (Text $field, NovaRequest $request, FormData $formData) {
                    if (!$formData->buyingForYourself) {
                        $field->show()->rules(['required', 'digits:11', 'numeric']);
                    }
                }),

            Text::make('Investor Name', 'investorName')
                ->hide()
                ->readonly(true)
                ->dependsOn(['buyingForYourself', 'investorPhoneNumber'], function (Text $field, NovaRequest $request, FormData $formData) {
                    if ($formData->buyingForYourself) {
                        $field->hide()->setValue(null);
                    } else {
                        if ($formData->investorPhoneNumber) {
                            if (strlen($formData->investorPhoneNumber) === 11) {

                                $user = User::role(RolesEnum::investor->name)->where('phoneNumber', $formData->investorPhoneNumber)->first();
                                if ($user && $user->id) {
                                    $field->show()->setValue($user->name);
                                } else {
                                    $field->show()->setValue('No User found with this phone number, please try again!');
                                }
                            } else {
                                $field->show()->setValue('Invalid Phone Number, should be 11 digits long, e.g. 03046619706');
                            }
                        } else {
                            $field->show()->setValue('No User found with this phone number, please try again!');
                        }
                    }
                }),

            Text::make('Broker Referral Code', 'referralCode')
                ->readonly()
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                })
                ->dependsOn(['buyingForYourself'], function (Text $field, NovaRequest $request, FormData $formData) {
                    if ($formData->buyingForYourself) {
                        $field->setValue(null);
                    } else {
                        $currentUser = $request->user();
                        if (ZHelpers::isBroker($currentUser)) {
                            $currentUserReferralCode = $currentUser->referralCode;
                            if ($currentUserReferralCode) {
                                $field->setValue($currentUserReferralCode);
                            } else {
                                $field->setValue('[ERROR] - referral code not found, userId = ' . $currentUser->id);
                            }
                        } else {
                            $field->setValue(null);
                        }
                    }
                }),
        ];
    }
}
