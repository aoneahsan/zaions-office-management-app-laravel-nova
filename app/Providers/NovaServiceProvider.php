<?php

namespace App\Providers;

use App\Nova\Dashboards\Main;
use App\Nova\FPI\Project;
use App\Nova\FPI\ProjectTransaction;
use App\Nova\Lenses\Projects\ProjectRebateListPageLens;
use App\Nova\User;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Vyuldashev\NovaPermission\NovaPermissionTool;
use Vyuldashev\NovaPermission\Permission;
use Vyuldashev\NovaPermission\Role;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // BreadCrumbs in App
        Nova::withBreadcrumbs(false);


        // LTR in App
        Nova::enableRTL(false);

        // set the timezone to user current timezone
        Nova::userTimezone(function (Request $request) {
            if ($request->user() && $request->user()->timezone) {
                return $request->user()->timezone;
            } else {
                return "Asia/Karachi";
            }
        });


        // disable the global search
        Nova::withoutGlobalSearch();

        // disable nova theme switcher
        Nova::withoutThemeSwitcher();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::withAuthentication();
        Route::namespace('Laravel\Nova\Http\Controllers')
            ->domain(config('nova.domain', null))
            ->middleware(config('nova.middleware', []))
            ->prefix(Nova::path())
            ->group(function (Router $router) {
                $router->post('/logout', [LoginController::class, 'logout'])->name('nova.logout');
            });

        Nova::routes()
            ->register();


        // Top Bar User Menu
        Nova::userMenu(function (Request $request, Menu $menu) {
            return $menu
                ->prepend(
                    MenuItem::externalLink('Edit Profile', '/profile')
                        ->canSee(function (Request $request) {
                            $currentUser = $request->user();
                            return $currentUser->hasPermissionTo(PermissionsEnum::update_profile->name);
                        })
                );
        });

        // Side Bar User Menu
        Nova::mainMenu(function (Request $request, Menu $menu) {
            return [
                MenuSection::make('Dashboard', [
                    MenuItem::dashboard(Main::class),
                    MenuGroup::make('Resources', [
                        MenuItem::resource(User::class)
                            ->canSee(function (Request $request) {
                                $currentUser = $request->user();
                                return $currentUser->hasPermissionTo(PermissionsEnum::viewResource_user->name);
                            }),
                        MenuItem::resource(Project::class)
                            ->canSee(function (Request $request) {
                                $currentUser = $request->user();
                                return $currentUser->hasPermissionTo(PermissionsEnum::viewResource_project->name);
                            }),
                        MenuItem::lens(Project::class, ProjectRebateListPageLens::class)
                            ->canSee(function (Request $request) {
                                $currentUser = $request->user();
                                return !$currentUser->hasPermissionTo(PermissionsEnum::viewResource_project->name) && $currentUser->hasPermissionTo(PermissionsEnum::viewLens_projectRebateListPage->name);
                            }),
                        MenuItem::resource(ProjectTransaction::class)
                            ->canSee(function (Request $request) {
                                $currentUser = $request->user();
                                return $currentUser->hasPermissionTo(PermissionsEnum::viewResource_projectTransaction->name);
                            }),
                    ]),

                    MenuGroup::make('My Account', [
                        MenuItem::externalLink('Edit Profile', '/profile')->canSee(function (Request $request) {
                            $currentUser = $request->user();
                            return $currentUser->hasPermissionTo(PermissionsEnum::update_profile->name);
                        }),
                        // disabled for now
                        // MenuItem::link('2FA', '/nova-two-factor')->canSee(function (Request $request) {
                        // $currentUser = $request->user();
                        // return $currentUser->hasPermissionTo(PermissionsEnum::view_2fa->name) && $currentUser->hasPermissionTo(PermissionsEnum::create_2fa->name);
                        //     return false; // disabled for now
                        // }),
                    ]),

                    // disabled for now
                    // MenuGroup::make('Admin Settings', [
                    //     MenuItem::resource(Role::class)->canSee(function (Request $request) {
                    //         $currentUser = $request->user();
                    //         return $currentUser->hasPermissionTo(PermissionsEnum::viewResource_role->name);
                    //     }),
                    //     MenuItem::resource(Permission::class)->canSee(function (Request $request) {
                    //         $currentUser = $request->user();
                    //         return $currentUser->hasPermissionTo(PermissionsEnum::viewResource_permission->name);
                    //     }),
                    // ]),
                ]),
            ];
        });
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->hasPermissionTo(PermissionsEnum::view_dashboard->name);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            // NovaPermissionTool::make()->canSee(function (Request $request) {
            // $currentUser = $request->user();
            // return $currentUser->hasPermissionTo(PermissionsEnum::viewAny_role->name) && $currentUser->hasPermissionTo(PermissionsEnum::viewAny_permission->name);
            //     return false; // disabled for now
            // }),

            // https://novapackages.com/packages/Visanduma/nova-two-factor
            // \Visanduma\NovaTwoFactor\NovaTwoFactor::make()->canSee(function (Request $request) {
            // $currentUser = $request->user();
            // return $currentUser->hasPermissionTo(PermissionsEnum::view_2fa->name) && $currentUser->hasPermissionTo(PermissionsEnum::create_2fa->name);
            //     return false; // disabled for now
            // }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Customize App Footer
        Nova::footer(function ($request) {
            return Blade::render('
            <div class="mt-8 leading-normal text-xs text-gray-500 space-y-1"><p class="text-center">Powered by <a class="link-default" href="https://zaions.com">Zaions</a>, Developed by <a class="link-default" href="https://aoneahsan.com">Ahsan Mahmood</a>.</p>
                <p class="text-center">© 2023 <a class="link-default" href="https://zaions.com">Zaions</a> by <a class="link-default" href="https://zaions.com/ahsan">Ahsan Mahmood</a>.</p>
            </div>
            ');
        });

        // Register Third party error logging
        Nova::report(function ($exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }
        });
    }
}
