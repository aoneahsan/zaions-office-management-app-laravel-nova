<?php

namespace App\Nova;

use App\Nova\Actions\DemoTestAction;
use App\Zaions\Helpers\ZHelpers;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Dniccum\PhoneNumber\PhoneNumber;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;

use Illuminate\Validation\Rules;
use InteractionDesignFoundation\HtmlCard\HtmlCard;
use Jeffbeltran\SanctumTokens\SanctumTokens;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Timezone;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\MorphToMany;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Pdmfc\NovaFields\ActionButton;
use Spatie\TagsField\Tags;
use Vyuldashev\NovaMoneyField\Money;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
use Whitecube\NovaFlexibleContent\Flexible;

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
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email',
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
            ID::make()->sortable()->sizeOnDetail('w-1/4'),

            Gravatar::make()->maxWidth(50)->sizeOnDetail('w-2/4'),

            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                })->sizeOnDetail('w-1/4'),

            Tabs::make('User Info', [
                Tab::make('General Info', [

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

                    Image::make('Profile Pitcher', 'profilePitcher')
                        ->rules('nullable', 'image', 'size:3000')
                        ->disk(ZHelpers::getActiveFileDriver())
                        ->maxWidth(300),
                    // Images::make('Profile Pitcher', 'profilePitcher') // second parameter is the media collection name
                    //     ->conversionOnIndexView('thumb') // conversion used to display the image
                    // ->disk(ZHelpers::getActiveFileDriver())
                    //     ->rules('nullable', 'image', 'size:3000'), // validation rules

                    // https://novapackages.com/packages/dniccum/phone-number
                    PhoneNumber::make('Phone Number', 'phoneNumber')
                        ->format('+## ### ### ####')
                        ->country('PK')
                ]),
                Tab::make('Other Info', [
                    Timezone::make('Timezone', 'timezone')->searchable()->default(ZHelpers::getTimezone()),

                    Number::make('dailyMinOfficeTime', 'dailyMinOfficeTime')
                        ->default(function () {
                            return 8;
                        })
                        ->min(3)
                        ->max(12)
                        ->step('any')
                        ->rules('required', 'numeric', 'min:3', 'max:12')
                        ->showOnIndex(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnCreating(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnUpdating(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnDetail(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        }),

                    Number::make('dailyMinOfficeTimeActivity', 'dailyMinOfficeTimeActivity')
                        ->default(function ($request) {
                            return 85;
                        })
                        ->min(70)
                        ->max(100)
                        ->step('any')
                        ->rules('required', 'numeric', 'min:70', 'max:100')
                        ->showOnIndex(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnCreating(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnUpdating(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        })
                        ->showOnDetail(function (NovaRequest $request) {
                            return ZHelpers::isNRUserSuperAdmin($request);
                        }),
                ]),
            ]),



            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json'),

            // https://novapackages.com/packages/whitecube/nova-flexible-content - working great
            // Flexible::make('Content')
            //     ->addLayout('Simple content section', 'wysiwyg', [
            //         Text::make('Title'),
            //         Markdown::make('Content')
            //     ])
            //     ->addLayout('Video section', 'video', [
            //         Text::make('Title'),
            //         Image::make('Video Thumbnail', 'thumbnail'),
            //         Text::make('Video ID (YouTube)', 'video'),
            //         Text::make('Video Caption', 'caption')
            //     ]),

            // https://novapackages.com/packages/spatie/nova-tags-field  -  search not working, tags adding working
            // Tags::make('Tags'),

            // https://novapackages.com/packages/vyuldashev/nova-money-field
            // Money::make('Display Name', 'EUR', 'columnName'),

            // https://novapackages.com/packages/pdmfc/nova-action-button
            // ActionButton::make('Export Action')
            //     ->action(DemoTestAction::class, $this->id),
            //->action(new ChangeRole(), $this->id) using a new instance

            // https://novapackages.com/packages/jeffbeltran/sanctum-tokens
            SanctumTokens::make(),

            HasMany::make('Tasks'),
            MorphMany::make('Comments'),
            MorphMany::make('Attachments'),

            MorphedByMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),
            MorphedByMany::make('Permissions', 'permissions', \Vyuldashev\NovaPermission\Permission::class),

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
        return [
            // https://novapackages.com/packages/coroowicaksono/chart-js-integration
            // https://coroo.github.io/nova-chartjs/#/?id=installation
            (new StackedChart())
                ->title('Revenue')
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => 'Product #1',
                    'backgroundColor' => '#ffcc5c',
                    'data' => [30, 70, 80],
                ], [
                    'barPercentage' => 0.5,
                    'label' => 'Product #2',
                    'backgroundColor' => '#ff6f69',
                    'data' => [40, 62, 79],
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => ['Jan', 'Feb', 'Mar']
                    ],
                ])
                ->width('1/3'),

            // https://novapackages.com/packages/interaction-design-foundation/nova-html-card
            (new HtmlCard())->width('1/3')->html('<h1>Hello World!</h1>'),
            (new HtmlCard())->width('1/3')->markdown('# Hello World!'),
            // (new HtmlCard())->width('1/3')->view('cards.hello', ['name' => 'World']),
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
        return [
            ExportAsCsv::make()->nameable()->withFormat(function ($model) {
                return [
                    'ID' => $model->getKey(),
                    'Name' => $model->name,
                    'Email Address' => $model->email,
                ];
            }),

        ];
    }
}
