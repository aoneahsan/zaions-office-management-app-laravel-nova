<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;

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
        // Nova::withBreadcrumbs();
        Nova::withBreadcrumbs(function (NovaRequest $request) {
            if ($request->user()) {
                return $request->user()->wantsBreadcrumbs();
            } else {
                return false;
            }
        });


        // LTR in App
        // Nova::enableRTL();
        Nova::enableRTL(function (Request $request) {
            if ($request->user()) {
                return $request->user()->wantsRTL();
            } else {
                return false;
            }
        });

        // Theme Switcher
        // Nova::withoutThemeSwitcher();

        // set the timezone to user current timezone
        Nova::userTimezone(function (Request $request) {
            if ($request->user() && $request->user()->timezone) {
                return $request->user()->timezone;
            } else {
                return "Asia/Karachi";
            }
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
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
            return in_array($user->email, [
                //
            ]);
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
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
