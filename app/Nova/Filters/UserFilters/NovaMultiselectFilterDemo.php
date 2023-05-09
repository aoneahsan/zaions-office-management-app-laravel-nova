<?php

namespace App\Nova\Filters\UserFilters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

// https://novapackages.com/packages/outl1ne/nova-multiselect-filter
class NovaMultiselectFilterDemo extends MultiselectFilter
{
    public function apply(Request $request, $query, $value)
    {
        // return $query->whereHas('books', function ($query) use ($value) {
        //     $query->whereIn('author_id', $value);
        // });
        return $query->whereIn('email', $value);
    }

    public function options(Request $request)
    {
        // return User::all()->pluck('name', 'id')
        return [
            'cat' => ['label' => 'Cat', 'group' => 'Pets'],
            'dog' => ['label' => 'Dog', 'group' => 'Pets'],
            'eagle' => ['label' => 'Eagle', 'group' => 'Birds'],
            'parrot' => ['label' => 'Parrot', 'group' => 'Birds'],
        ];
    }

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
}
