<?php

namespace App\Nova\FPI;

use App\Models\FPI\Project as FPIProject;
use App\Nova\Actions\FPI\Projects\ProjectUnitsPurchaseAction;
use App\Nova\Default\Attachment;
use App\Nova\Lenses\FPI\Projects\ProjectRebateListPageLens;
use App\Nova\User;
use App\Nova\Resource;
use App\Zaions\Enums\FPI\Projects\ProjectMeasuringUnitTypeEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FPI\Project>
     */
    public static $model = \App\Models\FPI\Project::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'location', 'type'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Owner', 'user', User::class)
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                })
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->withoutTrashed(),

            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),

            MorphMany::make('Attachments', 'attachments', Attachment::class),

            HasMany::make('Project Transactions', 'projectTransactions', ProjectTransaction::class)
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUserOrOwner($request->user(), $this->userId);
                }),

            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                })
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Text::make('Title', 'title')
                ->rules('required', 'max:255', 'string'),

            Trix::make('Description', 'description')
                ->rules('required', 'string', 'max:3000'),

            Number::make('Per Unit Price', 'perUnitPrice')
                ->min(0)
                ->rules('required', 'numeric'),

            Hidden::make('Unit Measured In', 'unitMeasuredIn')
                ->default(function () {
                    return ProjectMeasuringUnitTypeEnum::squareFeet->name;
                })
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Trix::make('Why Invest In This Project', 'whyInvest')
                ->rules('required', 'string', 'max:3000'),

            Text::make('Block Initial', 'blockName')
                ->rules('required', 'max:255', 'string')
                ->placeholder('Add Block Name initial - e.g. "C" for "C Block".'),

            Text::make('Location', 'location')
                ->rules('required', 'max:255'),

            Text::make('Type', 'type')
                ->rules('required', 'max:255'),

            // Text::make('Coordinates', 'coordinates')
            //     ->rules('required', 'max:255'),

            KeyValue::make('Bank Details', 'bankDetails')
                ->rules('required', 'json'),

            Number::make('Rebate Percentage', 'rebatePercentage')
                ->min(0)->max(100)
                ->rules('required', 'numeric'),

            Number::make('Total Units', 'totalUnits')
                ->min(0)
                ->rules('required', 'numeric'),

            Number::make('Remaining Units', 'remainingUnits')
                ->min(0)
                ->readonly()
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Number::make('Sold Units', 'soldUnits')
                ->exceptOnForms()
                ->min(0)
                ->rules('nullable', 'numeric')
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Number::make('Reserved Units', 'unitsReservedByUsers')
                ->exceptOnForms()
                ->min(0)
                ->rules('nullable', 'numeric')
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),


            Hidden::make('Sort Order No', 'sortOrderNo')->default(function () {
                $lastItem = FPIProject::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra attributes', 'extraAttributes')
                ->rules('nullable'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
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
            ProjectRebateListPageLens::make()
                ->canSee(function (Request $request) {
                    $currentUser = $request->user();
                    return $currentUser->hasPermissionTo(PermissionsEnum::viewLens_projectRebateListPage->name);
                })
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
            ProjectUnitsPurchaseAction::make()
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUserOrNotOwner($request->user(), $this->userId);
                }),
        ];
    }
}
