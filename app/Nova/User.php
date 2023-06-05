<?php

namespace App\Nova;

use App\Models\User as ModelsUser;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class User extends Resource
{
    use HasSortableRows;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return 'Name: ' . $this->name;
    }

    public function subtitle()
    {
        return 'Phone Number: ' . $this->phoneNumber;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email', 'phoneNumber', 'cnic'
    ];

    /**
     * The number of results to display when searching for relatable resources without Scout.
     *
     * @var int|null
     */
    public static $relatableSearchResults = 1;
    public static $displayInNavigation = true;
    public static $globallySearchable = false;
    public static $globalSearchResults = 1;
    public static $scoutSearchResults = 1;
    public static $searchable = true;

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (ZHelpers::isNRUserSuperAdmin($request)) {
            return $query;
        } else {
            return $query->whereNotIn('email', ['superadmin@zaions.com', 'ahsan@zaions.com']);
        }
    }

    public static function scoutQuery(NovaRequest $request, $query)
    {
        if (ZHelpers::isNRUserSuperAdmin($request)) {
            return $query;
        } else {
            return $query->whereNotIn('email', ['superadmin@zaions.com', 'ahsan@zaions.com']);
        }
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        if (ZHelpers::isNRUserSuperAdmin($request)) {
            return $query;
        } else {
            return $query->whereNotIn('email', ['superadmin@zaions.com', 'ahsan@zaions.com']);
        }
    }

    public static function relatableQuery(NovaRequest $request, $query)
    {
        if (ZHelpers::isNRUserSuperAdmin($request)) {
            return $query;
        } else {
            return $query->whereNotIn('email', ['superadmin@zaions.com', 'ahsan@zaions.com']);
        }
    }

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

            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                })
                ->canSee(function (NovaRequest $request) {
                    return ZHelpers::isAdminLevelUser($request->user());
                }),

            Text::make('Role', 'assignedRoleName')
                ->exceptOnForms()
                ->canSee(function (Request $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Text::make('Slug')
                ->sortable()
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->filterable(function ($request, $query, $value, $attribute) {
                    return $query->where($attribute, 'LIKE', "%{$value}%");
                }),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults())
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Text::make('Phone Number', 'phoneNumber')
                ->rules('required', 'digits:11', Rule::unique(ModelsUser::class)->ignore($this->id)),

            Text::make('CNIC', 'cnic')
                ->rules('required', 'digits:13', Rule::unique(ModelsUser::class)->ignore($this->id)),

            Text::make('Referral Code', 'referralCode')
                ->exceptOnForms()
                ->copyable(),

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
        return $this->myFilters();
    }

    protected function myFilters()
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
