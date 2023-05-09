<?php

namespace App\Nova;

use AlexAzartsev\Heroicon\Heroicon;
use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Nova\Actions\DemoTestAction;
use App\Nova\Filters\UserFilters\CustomInputFilter;
use App\Nova\Filters\UserFilters\NovaMultiselectFilterDemo;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Degecko\NovaFiltersSummary\FiltersSummary;
use Dniccum\PhoneNumber\PhoneNumber;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;

use Illuminate\Validation\Rules;
use InteractionDesignFoundation\HtmlCard\HtmlCard;
use InteractionDesignFoundation\NovaUnlayerField\Unlayer;
use InteractionDesignFoundation\WorldClockCard\WorldClock;
use Jeffbeltran\SanctumTokens\SanctumTokens;
use Khalin\Nova4SearchableBelongsToFilter\NovaSearchableBelongsToFilter;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
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
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Trix;
use LimeDeck\NovaCashierOverview\Subscription;
use Mostafaznv\NovaVideo\Video;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Oneduo\NovaFileManager\FileManager;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;
use Outl1ne\NovaInputFilter\InputFilter;
use Outl1ne\NovaNotesField\NotesField;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Pdmfc\NovaFields\ActionButton;
use Razorcreations\AjaxField\AjaxField;
use SadekD\NovaOpeningHoursField\NovaOpeningHoursField;
use Sietse85\NovaButton\Button;
use SLASH2NL\NovaBackButton\NovaBackButton;
use Spatie\TagsField\Tags;
use Stepanenko3\NovaJson\JSON;
use Vyuldashev\NovaMoneyField\Money;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
use Wdelfuego\Nova\DateTime\Fields\DateTime;
use Webparking\BelongsToDependency\BelongsToDependency;
use WesselPerik\StatusField\StatusField;
use Whitecube\NovaFlexibleContent\Flexible;
use YieldStudio\NovaGoogleAutocomplete\AddressMetadata;
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

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

            // Relationship fields below
            // HasMany::make('Tasks'),
            // MorphMany::make('Comments'),
            // MorphMany::make('Attachments'),

            // MorphedByMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),
            // MorphedByMany::make('Permissions', 'permissions', \Vyuldashev\NovaPermission\Permission::class),

            // Packages Fields below

            // StatusField::make('Published', 'name')
            //     ->icons([
            //         'minus-circle' => false,
            //         // 'clock'        => $this->pending == 1 && $this->published == 0,
            //         'clock'        => false,
            //         'check-circle' => true
            //     ])
            //     ->tooltip([
            //         'minus-circle' => 'Not published',
            //         'clock'        => 'Pending publication',
            //         'check-circle' => 'Published'
            //     ])
            //     ->info([
            //         'minus-circle' => 'This blog is not published yet.',
            //         'clock'        => 'This blog is pending publication.',
            //         // 'check-circle' => 'This blog is published on ' . $this->published_at->format('d-m-Y') . '.'
            //         'check-circle' => 'This blog is published on today.'
            //     ])
            //     ->color([
            //         'minus-circle' => 'red-500',
            //         'clock'        => 'blue-500',
            //         'check-circle' => 'green-500'
            //     ]),

            // https://novapackages.com/packages/whitecube/nova-flexible-content - working great
            // Flexible::make('Content', 'flexableContent')
            //     ->addLayout('Simple content section', 'wysiwyg', [
            //         StatusField::make('Published', 'name')
            //             ->icons([
            //                 'minus-circle' => false,
            //                 // 'clock'        => $this->pending == 1 && $this->published == 0,
            //                 'clock'        => false,
            //                 'check-circle' => true
            //             ])
            //             ->tooltip([
            //                 'minus-circle' => 'Not published',
            //                 'clock'        => 'Pending publication',
            //                 'check-circle' => 'Published'
            //             ])
            //             ->info([
            //                 'minus-circle' => 'This blog is not published yet.',
            //                 'clock'        => 'This blog is pending publication.',
            //                 // 'check-circle' => 'This blog is published on ' . $this->published_at->format('d-m-Y') . '.'
            //                 'check-circle' => 'This blog is published on today.'
            //             ])
            //             ->color([
            //                 'minus-circle' => 'red-500',
            //                 'clock'        => 'blue-500',
            //                 'check-circle' => 'green-500'
            //             ]),
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
            // SanctumTokens::make(),


            // https://novapackages.com/packages/limedeck/nova-cashier-overview
            // if you want to display a specific subscription or multiple
            // Subscription::make('a-fancy-subscription-name'),

            // https://novapackages.com/packages/kirschbaum-development/nova-comments
            // new Commenter(),

            // https://novapackages.com/packages/razorcreations/ajax-field
            // AjaxField::make('Foo')->setUrl('/api/ajaxselect/foo')->setValueKey('id')->setLabelKey('name'),

            // https://novapackages.com/packages/yieldstudio/nova-google-autocomplete
            // Now this address field will search and store the address as a string, but also made available the values in the withValues array
            GoogleAutocomplete::make('Address', 'address')->withValues(['latitude', 'longitude']),
            // // And you can store the values by autocomplete like this
            // AddressMetadata::make('lat')->fromValue('latitude'),
            // AddressMetadata::make('long')->fromValue('longitude'),
            // // You can disable the field so the user can't edit the metadata
            // AddressMetadata::make('long')->fromValue('longitude')->disabled(),
            // // Or you can make the field invisible in the form but collect the data anyways
            // AddressMetadata::make('long')->fromValue('longitude')->invisible(),
            // AddressMetadata::make('coordinates')->fromValue('{{latitude}}, {{longitude}}'),


            // https://novapackages.com/packages/stepanenko3/nova-json
            JSON::make('Author', 'jsonFieldContent', [
                Text::make('Name')->rules(['string', 'required', 'min:3']),
                Text::make('Email')->rules(['email', 'required']),
            ]),

            // https://novapackages.com/packages/webparking/nova-belongs-to-dependency
            // BelongsToDependency::make('User')
            //     ->dependsOn('type', 'type_id'),

            // https://novapackages.com/packages/kongulov/nova-tab-translatable
            // NovaTabTranslatable::make([
            //     Text::make('Title', 'randomDataTesting'),
            // ]),

            // https://novapackages.com/packages/mostafaznv/nova-video
            Video::make(trans('Video'), 'randomDataTesting', 'media')
                ->rules('file', 'max:150000', 'mimes:mp4', 'mimetypes:video/mp4')
                ->creationRules('required')
                ->updateRules('nullable'),

            // https://novapackages.com/packages/wdelfuego/nova-datetime
            // DateTime::make(__('Localized label'), 'attribute')
            //     ->withDateFormat('d-M-Y, H:i'),

            // https://novapackages.com/packages/oneduo/nova-file-manager
            FileManager::make(__('Avatar'), 'randomDataTesting'),

            // https://novapackages.com/packages/alexazartsev/heroicon
            Heroicon::make('Icon'),


            // https://novapackages.com/packages/outl1ne/nova-notes-field
            NotesField::make('Notes', 'notesFieldData'),
            // ->placeholder('Add note') // Optional
            // ->addingNotesEnabled(false) // Optional
            // ->fullWidth(true), // Optional

            // https://novapackages.com/packages/sadekd/nova-opening-hours-field
            NovaOpeningHoursField::make(__('Opening Hours'), 'openingHoursData'),
            // ->allowExceptions(FALSE)    // TRUE by default
            // ->allowOverflowMidnight(TRUE)  // FALSE by default
            // ->useTextInputs(TRUE)  // FALSE by default


            // https://novapackages.com/packages/interaction-design-foundation/nova-unlayer-field
            // Unlayer::make('Content', 'design')->config([
            //     'projectId' => config('unlayer.project_id'),

            //     // optional
            //     'templateId' => config('unlayer.default_template_id'), // Used only if bound attribute ('design' in this case) is empty.
            //     'displayMode' => 'web', // "email" or "web". Default value: "email"
            //     'locale' => 'es', // Locale for Unlayer UI. Default value: application’s locale.
            // ]),

            // https://novapackages.com/packages/alexwenzel/nova-dependency-container
            // DependencyContainer::make([
            //     Text::make('First Name', 'name')
            // ])->dependsOn('email', 'ahsan@zaions.com')
            //             ->dependsOn('field1', 'value1')
            // ->dependsOnNotEmpty('field2')
            // ->dependsOn('field3', 'value3'),


            // https://novapackages.com/packages/sietse85/nova-button
            Button::make('Zaions')->link('https://nova.laravel.com')->confirm('Confirmation', 'Are you sure you want to cancel your account?', 'Cancel'),
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

            // https://novapackages.com/packages/degecko/nova-filters-summary
            FiltersSummary::make(),

            // https://novapackages.com/packages/slash2nl/nova-back-button
            new NovaBackButton(),

            // https://novapackages.com/packages/outl1ne/nova-detached-filters
            new NovaDetachedFilters($this->myFilters()),

            // https://novapackages.com/packages/nemrutco/nova-global-filter
            // new NovaGlobalFilter([
            //     new Date, // Date Filter
            // ])

            // https://novapackages.com/packages/interaction-design-foundation/nova-worldclock-card
            (new WorldClock())
                ->timezones([
                    'Asia/Dubai',
                    'America/New_York',
                    'Europe/Kiev',
                ]),
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
        return $this->myFilters();
    }

    protected function myFilters()
    {
        return [
            // https://novapackages.com/packages/outl1ne/nova-input-filter
            InputFilter::make()->forColumns(['email'])->withName('Email'),
            CustomInputFilter::make(),

            // https://novapackages.com/packages/khalin/nova4-searchable-belongs-to-filter
            // (new NovaSearchableBelongsToFilter('my-new-name'))
            //     ->fieldAttribute('department')
            //     ->filterBy('department_id')


            // https://novapackages.com/packages/outl1ne/nova-multiselect-filter
            // NovaMultiselectFilterDemo::make()
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
