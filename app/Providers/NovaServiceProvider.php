<?php

namespace App\Providers;

use App\Zaions\Enums\PermissionsEnum;
use Bakerkretzmar\NovaSettingsTool\SettingsTool;
use CodencoDev\NovaGridSystem\NovaGridSystem;
use Dniccum\NovaDocumentation\NovaDocumentation;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Oneduo\NovaFileManager\NovaFileManager;
use Vyuldashev\NovaPermission\NovaPermissionTool;

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
            NovaPermissionTool::make(),

            // https://novapackages.com/packages/spatie/nova-backup-tool
            // new \Spatie\BackupTool\BackupTool(),

            // https://novapackages.com/packages/vmitchell85/nova-links
            (new \vmitchell85\NovaLinks\Links('Documentation'))
                ->addExternalLink('Zaions', 'https://zaions.com', true),

            // https://novapackages.com/packages/bolechen/nova-activitylog
            new \Bolechen\NovaActivitylog\NovaActivitylog(),

            // https://novapackages.com/packages/bakerkretzmar/nova-settings-tool
            new SettingsTool,

            // https://novapackages.com/packages/codenco-dev/nova-grid-system#screenshots
            new NovaGridSystem,

            // https://novapackages.com/packages/outl1ne/nova-menu-builder
            // mainly to create menus in laravel nova and then get them in frontend using API
            // \Outl1ne\MenuBuilder\MenuBuilder::make()
            //     // Optional customization
            //     ->title('Menus') // Define a new name for sidebar
            //     ->icon('adjustments') // Customize menu icon, supports heroicons
            //     ->hideMenu(false), // Hide MenuBuilder defined MenuSection.

            // https://novapackages.com/packages/Visanduma/nova-two-factor
            new \Visanduma\NovaTwoFactor\NovaTwoFactor(),

            // https://novapackages.com/packages/oneduo/nova-file-manager
            NovaFileManager::make(),

            // https://novapackages.com/packages/whitecube/nova-page
            \Whitecube\NovaPage\NovaPageTool::make(),


            // https://novapackages.com/packages/dniccum/nova-documentation
            new NovaDocumentation,

        ];
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
