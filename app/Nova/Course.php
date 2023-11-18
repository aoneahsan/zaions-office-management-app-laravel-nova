<?php

namespace App\Nova;

use App\Models\Course as ModelsCourse;
use App\Zaions\Enums\CourseCategoryEnum;
use App\Zaions\Helpers\ZHelpers;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaNotesField\NotesField;
use Spatie\TagsField\Tags;

class Course extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Course>
     */
    public static $model = \App\Models\Course::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public  function title()
    {
        return 'Title: ' . $this->title . ' | Duration: ' . $this->duration;
    }
    public  function subtitle()
    {
        return 'Category: ' . $this->category . ' | price: ' . $this->price . ' | dis-price: ' . $this->discountPrice . ' | dis-percentage: ' . $this->discountPercentage . ' | Enabled: ' . $this->isActive;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'category', 'tags'
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
                $lastItem = ModelsCourse::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),
            Text::make('Notebook Title', 'title')->rules('required'),
            Trix::make('Description', 'description')->rules('required'),
            Number::make('Actual Price', 'price')->rules('required'),
            Number::make('Discounted Price', 'discountPrice')->rules('required'),
            Text::make('Duration', 'duration')->rules('required'),
            Select::make('Parent Category', 'category')->options([
                CourseCategoryEnum::development->name => CourseCategoryEnum::development->name,
                CourseCategoryEnum::videoEditing->name => CourseCategoryEnum::videoEditing->name,
                CourseCategoryEnum::designing->name => CourseCategoryEnum::designing->name,
                CourseCategoryEnum::marketing->name => CourseCategoryEnum::marketing->name,
                CourseCategoryEnum::testing->name => CourseCategoryEnum::testing->name,
                CourseCategoryEnum::seo->name => CourseCategoryEnum::seo->name,
                CourseCategoryEnum::eCommerce->name => CourseCategoryEnum::eCommerce->name,
                CourseCategoryEnum::writing->name => CourseCategoryEnum::writing->name,
                CourseCategoryEnum::freelancing->name => CourseCategoryEnum::freelancing->name,
                CourseCategoryEnum::other->name => CourseCategoryEnum::other->name,
            ])->rules('required'),
            Text::make('Tags', 'tags')->rules('required'),

            URL::make('Course Url', 'url')->rules('url', 'nullable'),

            Number::make('discountPercentage', 'discountPercentage'),

            NotesField::make('Notes')
                ->placeholder('Add note') // Optional
                ->addingNotesEnabled(true) // Optional
                ->fullWidth(), // Optional
            MorphMany::make('Course Attachments', 'attachments', Attachment::class),

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
