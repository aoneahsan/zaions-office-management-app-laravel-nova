<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\FPI\TrendMetrics\Projects\ProjectCountTrendMetrics;
use App\Nova\Metrics\FPI\TrendMetrics\UserCountTrendMetrics;
use App\Nova\Metrics\FPI\ValueMetrics\Projects\ProjectCountValueMetrics;
use App\Nova\Metrics\FPI\ValueMetrics\UserCountValueMetrics;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Http\Request;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Zaions\Welcomecard\Welcomecard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            // new \Richardkeep\NovaTimenow\NovaTimenow,
            // (new \Richardkeep\NovaTimenow\NovaTimenow)->timezones([
            //     'Africa/Nairobi',
            //     'America/Mexico_City',
            //     'Australia/Sydney',
            //     'Europe/Paris',
            //     'Asia/Tokyo',
            // ])->defaultTimezone('Africa/Nairobi'),

            UserCountValueMetrics::make()->width('1/2')->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(PermissionsEnum::viewAny_user->name);
            }),
            ProjectCountValueMetrics::make()->width('1/2')->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(PermissionsEnum::viewAny_project->name);
            }),
            UserCountTrendMetrics::make()->width('1/2')->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(PermissionsEnum::viewAny_user->name);
            }),
            ProjectCountTrendMetrics::make()->width('1/2')->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(PermissionsEnum::viewAny_project->name);
            }),

            Welcomecard::make()->canSee(function (Request $request) {
                $currentUser = $request->user();
                return !$currentUser->hasPermissionTo(PermissionsEnum::viewAny_user->name) && !$currentUser->hasPermissionTo(PermissionsEnum::viewAny_project->name);
            })

        ];
    }
}
