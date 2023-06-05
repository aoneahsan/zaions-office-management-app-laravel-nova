<?php

namespace App\Nova\FPI;

use App\Models\FPI\ProjectTransaction as FPIProjectTransaction;
use App\Nova\Resource;
use App\Nova\User;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProjectTransaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FPI\ProjectTransaction>
     */
    public static $model = \App\Models\FPI\ProjectTransaction::class;

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
        'id',
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

            HasOne::make('Project', 'project', Project::class),
            HasOne::make('Seller', 'seller', User::class),
            HasOne::make('Buyer', 'buyer', User::class),


            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                })
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Number::make('Units Before Transaction', 'unitsBeforeTransaction')
                ->readonly(true),

            Number::make('Units After Transaction', 'unitsAfterTransaction')
                ->readonly(true),

            Number::make('Units Purchased In Transaction', 'unitsBoughtInTransaction')
                ->readonly(true),

            Text::make('Serial Number Starts From', 'unitsSerialNumberStartsFrom')
                ->readonly(true),

            Text::make('Serial Number Ends At', 'unitsSerialNumberEndsAt')
                ->readonly(true),

            Number::make('Per Unit Price', 'perUnitPrice')
                ->readonly(true),

            Text::make('Unit Measured In', 'unitMeasuredIn')
                ->readonly(true),

            Text::make('Status', 'status')
                ->readonly(true),

            Text::make('Transaction Type', 'transactionType')
                ->readonly(true),

            Hidden::make('Sort Order No', 'sortOrderNo')->default(function () {
                $lastItem = FPIProjectTransaction::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra attributes', 'extraAttributes')
                ->rules('nullable')
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),
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
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
