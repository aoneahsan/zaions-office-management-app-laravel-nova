<?php

namespace App\Nova\Default;

use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Timezone;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkSpace extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\WorkSpace>
     */
    public static $model = \App\Models\Default\WorkSpace::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title'
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

            Gravatar::make()->maxWidth(50),

            // Relationship Fields

            // Hidden fields
            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = Workspace::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),


            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                }),

            Text::make('title')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Timezone::make('Timezone', 'timezone')->searchable()->default(ZHelpers::getTimezone()),


            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
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
