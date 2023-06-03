<?php

namespace App\Nova\Lenses\Projects;

use App\Nova\Default\Attachment;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;

class ProjectRebateListPageLens extends Lens
{
    public function name()
    {
        return 'Projects Rebate Page';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'location', 'type'
    ];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(Nova::__('ID'), 'id')->sortable(),

            MorphMany::make('Attachments', 'attachments', Attachment::class),


            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                }),

            Text::make('Title', 'title')
                ->rules('required', 'max:255', 'string'),

            // Trix::make('Description', 'description')
            //     ->rules('required', 'string', 'max:3000'),

            // Number::make('Per Square Feet Price', 'perSquareFeetPrice')
            //     ->min(0)
            //     ->rules('required', 'numeric'),

            // Trix::make('Why Invest In This Project', 'whyInvest')
            //     ->rules('required', 'string', 'max:3000'),

            Text::make('Location', 'location')
                ->rules('required', 'max:255'),

            Text::make('Type', 'type')
                ->rules('required', 'max:255'),

            // Text::make('Coordinates', 'coordinates')
            //     ->rules('required', 'max:255'),

            // KeyValue::make('Bank Details', 'bankDetails')
            //     ->rules('required', 'json'),

            Number::make('Rebate Percentage', 'rebatePercentage')
                ->min(0)->max(100)
                ->rules('required', 'numeric'),

            // Number::make('Total Units', 'totalUnits')
            //     ->min(0)
            //     ->rules('required', 'numeric'),

            // Number::make('Remaining Units', 'remainingUnits')
            //     ->exceptOnForms()
            //     ->min(0)
            //     ->rules('nullable', 'numeric'),

            // KeyValue::make('Extra attributes', 'extraAttributes')
            //     ->rules('nullable'),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'rebate';
    }
}
