<?php

namespace App\Nova;

use App\Models\Note as ModelsNote;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaNotesField\NotesField;

class Note extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Note>
     */
    public static $model = \App\Models\Note::class;

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
            // Hidden Fields
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),
            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ModelsNote::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),
            Text::make('Notebook Title', 'title'),
            NotesField::make('Notes')
                ->placeholder('Add note') // Optional
                ->addingNotesEnabled(true) // Optional
                ->fullWidth(), // Optional

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json'),
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
