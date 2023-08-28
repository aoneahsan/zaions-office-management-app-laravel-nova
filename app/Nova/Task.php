<?php

namespace App\Nova;

use App\Models\Task as ModelsTask;
use App\Models\User as ModelsUser;
use App\Nova\Actions\TaskActions\ApproveTaskAction;
use App\Nova\Actions\TaskActions\ReviewTaskAction;
use App\Nova\Filters\TaskFilters\TaskStatusFilter;
use App\Nova\Filters\TaskFilters\TaskTypeFilter;
use App\Nova\Filters\TaskFilters\TaskVerificationStatusFilter;
use App\Nova\Lenses\TaskLens\PendingTaskLens;
use App\Nova\Metrics\PartitionMetrics\TasksPerStatusPartitionMetrics;
use App\Nova\Metrics\TrendMetrics\TasksPerWeekTrendMetrics;
use App\Nova\Metrics\ValueMetrics\TaskCountValueMetrics;
use App\Zaions\Enums\NamazEnum;
use App\Zaions\Enums\TaskStatusEnum;
use App\Zaions\Enums\TaskTypeEnum;
use App\Zaions\Enums\VerificationStatusEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class Task extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Task>
     */
    public static $model = \App\Models\Task::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->id . ': ' . $this->title;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        $assignedUser = $this->assignedUser;
        return 'Assigned To: ' . $assignedUser->name . '(' . $assignedUser->email . ')';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'status', 'type'
    ];

    /**
     * The number of results to display when searching for relatable resources without Scout.
     *
     * @var int|null
     */
    public static $relatableSearchResults = 10;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()
                ->sortable(),

            // Relationship Fields
            HasMany::make('Task History Record', 'history', History::class),
            MorphMany::make('Task Comments', 'comments', Comment::class),
            MorphMany::make('Task Attachments', 'attachments', Attachment::class),
            HasOne::make('Verifier User', 'verifier', User::class)
                ->hideFromIndex()
                ->showOnCreating(false)
                ->showOnUpdating(false)
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            HasOne::make('Approver User', 'approver', User::class)
                ->hideFromIndex()
                ->showOnCreating(false)
                ->showOnUpdating(false)
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            BelongsTo::make('Created By', 'user', User::class)
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                })
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            HasOne::make('Assigned To', 'assignedUser', User::class)
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                })
                ->showOnCreating(false)
                ->showOnUpdating(false)
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            // Hidden Fields
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),

            Hidden::make('namazOfferedAt', 'namazOfferedAt')
                ->rules('nullable')
                ->dependsOn('type', function (Hidden $thisField, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::namaz->name) {
                        $thisField->rules('required')->default(Carbon::now());
                    }
                })
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::namaz->name;
                }),
            Hidden::make('weekOfYear', 'weekOfYear')
                ->default(function () {
                    return Carbon::now()->weekOfYear;
                }),
            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ModelsTask::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),

            MultiSelect::make('Send Notification To', 'sendNotificationToTheseUsers')
                ->options(function () {
                    return ModelsUser::where('isActive', true)->pluck('name', 'id');
                }),

            Text::make('Title', 'title')
                ->maxlength(250)
                ->rules('required', 'max:250'),

            Textarea::make('Task Info', 'detail')
                ->rules('nullable', 'string')
                ->maxlength(1000)
                ->enforceMaxlength()
                ->hideFromIndex()
                ->alwaysShow(),

            // Normal Form Fields
            Select::make('Assign To', 'assignedTo')
                ->searchable()
                ->rules('required')
                ->options(function () {
                    return ModelsUser::where('isActive', true)->pluck('name', 'id');
                })
                ->displayUsingLabels(),

            Select::make('Type')
                ->searchable()
                ->rules('required', 'string', new Enum(TaskTypeEnum::class))
                ->options(function () {
                    $currentUser = Auth::user();
                    $improvementProgramTasks = [
                        TaskTypeEnum::namaz->name => ['label' => 'Namaz', 'group' => 'Improvement'],
                        TaskTypeEnum::exercise->name => ['label' => 'Exercise', 'group' => 'Improvement'],
                        TaskTypeEnum::quran->name => ['label' => 'Quran', 'group' => 'Improvement']
                    ];

                    $generalTasks = [
                        TaskTypeEnum::dailyOfficeTime->name => ['label' => 'Daily Office Time', 'group' => 'Office'],
                        TaskTypeEnum::course->name => ['label' => 'Course', 'group' => 'Office'],
                        TaskTypeEnum::officeWorkTask->name => ['label' => 'Office Work Task', 'group' => 'Office'],
                        TaskTypeEnum::other->name => ['label' => 'Other', 'group' => 'Office'],
                    ];

                    if ($currentUser->isImprovementContractMember) {
                        return array_merge($generalTasks, $improvementProgramTasks);
                    }

                    return $generalTasks;
                })
                ->displayUsingLabels(),

            Select::make('Task Status', 'status')
                ->default(TaskStatusEnum::todo->name)
                ->rules('required', new Enum(TaskStatusEnum::class))
                ->options([
                    TaskStatusEnum::todo->name => TaskStatusEnum::todo->name,
                    TaskStatusEnum::inProgress->name => TaskStatusEnum::inProgress->name,
                    TaskStatusEnum::requireInfo->name => TaskStatusEnum::requireInfo->name,
                    TaskStatusEnum::availableForReview->name => TaskStatusEnum::availableForReview->name,
                    TaskStatusEnum::done->name => TaskStatusEnum::done->name,
                    TaskStatusEnum::closed->name => TaskStatusEnum::closed->name,
                    TaskStatusEnum::other->name => TaskStatusEnum::other->name,
                ])
                ->dependsOn('type', function (Select $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::namaz->name || $formData->type === TaskTypeEnum::quran->name || $formData->type === TaskTypeEnum::exercise->name || $formData->type === TaskTypeEnum::dailyOfficeTime->name) {
                        $field->default(TaskStatusEnum::done->name);
                    }
                })
                ->displayUsingLabels()
                ->searchable(),

            Select::make('Verification Status', 'verificationStatus')
                ->default(VerificationStatusEnum::pending->name)
                ->hide()
                ->options([
                    VerificationStatusEnum::pending->name => VerificationStatusEnum::pending->name,
                    VerificationStatusEnum::verified->name => VerificationStatusEnum::verified->name,
                    VerificationStatusEnum::approved->name => VerificationStatusEnum::approved->name
                ])
                ->displayUsingLabels()
                ->searchable(),

            Select::make('Namaz Offered', 'namazOffered')
                ->searchable()
                ->hide()
                ->readonly(true)
                ->rules('nullable')
                ->dependsOn('type', function (Select $thisField, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::namaz->name) {
                        $thisField->readonly(false)
                            ->show()
                            ->rules('required', 'string', new Enum(NamazEnum::class))
                            ->options(function () use ($request) {
                                $fieldOptions = [
                                    NamazEnum::fajar->name => NamazEnum::fajar->name,
                                    NamazEnum::zohar->name => NamazEnum::zohar->name,
                                    NamazEnum::asar->name => NamazEnum::asar->name,
                                    NamazEnum::magrib->name => NamazEnum::magrib->name,
                                    NamazEnum::isha->name => NamazEnum::isha->name,
                                    NamazEnum::juma->name => NamazEnum::juma->name
                                ];

                                return $fieldOptions;
                            });
                    }
                })
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::namaz->name;
                })
                ->displayUsingLabels(),

            Text::make('Remarks By Verifier', 'verifierRemarks')
                ->hideFromIndex(true)
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request) && $this->verifierRemarks !== null;
                })
                ->hideWhenCreating(true)
                ->hideWhenUpdating(true),

            Text::make('Remarks By Approver', 'approverRemarks')
                ->hideFromIndex(true)
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request) && $this->approverRemarks !== null;
                })
                ->hideWhenCreating(true)
                ->hideWhenUpdating(true),

            Date::make('Course Start Date', 'courseStartDate')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->hideWhenCreating(true)
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::course->name;
                })
                ->dependsOn('type', function (Date $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::course->name) {
                        $field->readonly(false)
                            ->hideWhenCreating(false)
                            ->rules('required')
                            ->show()
                            ->min(Carbon::now())
                            ->max(Carbon::now()->addMonths(12));
                    }
                }),

            Date::make('Course Estimate Date', 'courseEstimateDate')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::course->name;
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->dependsOn('type', function (Date $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::course->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(Carbon::now()->addDays(1))
                            ->max(Carbon::now()->addMonths(24));
                    }
                }),

            Number::make('Course Total Time In Hours', 'courseTotalTimeInHours')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::course->name;
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::course->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(1)
                            ->max(200);
                    }
                }),

            Number::make('Per Day Course Content Time In Hours', 'perDayCourseContentTimeInHours')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::course->name;
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::course->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(1)
                            ->max(8);
                    }
                }),

            Number::make('Number of days allowed for Course', 'numberOfDaysAllowedForCourse')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::course->name;
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::course->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(1)
                            ->max(50);
                    }
                }),


            Number::make('Time Spend On Exercise In Minutes', 'timeSpendOnExerciseInMinutes')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::exercise->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::exercise->name) {
                        $field->readonly(false)
                            ->show()
                            ->rules('required')
                            ->min(1);
                    }
                }),


            Number::make('Time Spend While Reading Quran In Minutes', 'timeSpendWhileReadingQuranInMinutes')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::quran->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::quran->name) {
                        $field->readonly(false)
                            ->show()
                            ->rules('required')
                            ->min(1);
                    }
                }),

            Number::make('work Time Recorded On Traqq', 'workTimeRecordedOnTraqq')
                ->readonly(true)
                ->hide()
                ->rules('nullable')
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::dailyOfficeTime->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::dailyOfficeTime->name) {
                        $field->readonly(false)
                            ->show()
                            ->rules('required')
                            ->min(0)
                            ->max(18);
                    }
                }),

            Number::make('Traqq Activity For Recorded Time', 'traqqActivityForRecordedTime')
                ->readonly(true)
                ->hide()
                ->rules('nullable')
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::dailyOfficeTime->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::dailyOfficeTime->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(0)
                            ->max(100);
                    }
                }),

            URL::make('Trello Ticket Link', 'trelloTicketLink')
                ->rules('nullable', 'url')
                ->showOnDetail(function () {
                    return $this->type === TaskTypeEnum::officeWorkTask->name;
                }),

            Image::make('Attachment', 'screenShot')
                ->rules('nullable', 'image')
                ->disk(ZHelpers::getActiveFileDriver())
                ->dependsOn('type', function (Image $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === TaskTypeEnum::dailyOfficeTime->name) {
                        $field->creationRules('required', 'image')->updateRules('nullable', 'image')
                            ->help('Attach the screen shot of traqq page showing current date recorded time and activity properly.');
                    }
                })
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return $this->screenShot !== null;
                }),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json')
                ->hideFromIndex()
                ->showOnDetail(function () {
                    return $this->extraAttributes !== null;
                }),

        ];
        // return [];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [

            TaskCountValueMetrics::make(),
            TasksPerWeekTrendMetrics::make(),
            TasksPerStatusPartitionMetrics::make()
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            TaskTypeFilter::make(),
            TaskStatusFilter::make(),
            TaskVerificationStatusFilter::make()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            PendingTaskLens::make()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            ReviewTaskAction::make()
                ->canSee(function (NovaRequest $request) {
                    $currentUserId = $request->user()->id;
                    return ($this->userId !== $currentUserId || ZHelpers::isNRUserSuperAdmin($request)) && $this->verificationStatus === VerificationStatusEnum::pending->name;
                })
                ->canRun(function (NovaRequest $request) {
                    $currentUserId = $request->user()->id;
                    return ($this->userId !== $currentUserId || ZHelpers::isNRUserSuperAdmin($request)) && $this->verificationStatus === VerificationStatusEnum::pending->name;
                }),
            ApproveTaskAction::make()
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->canRun(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request) && $this->verificationStatus === VerificationStatusEnum::verified->name;
                })
        ];
    }
}
