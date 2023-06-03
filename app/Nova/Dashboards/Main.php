<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ValueMetrics\Projects\ProjectCountValueMetrics;
use App\Nova\Metrics\ValueMetrics\UserCountValueMetrics;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Http\Request;
use Laravel\Nova\Dashboards\Main as Dashboard;

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

            UserCountValueMetrics::make()->canSee(function (Request $request) {
                $request->user()->hasPermissionTo(PermissionsEnum::viewAny_user->name);
            }),
            ProjectCountValueMetrics::make()->canSee(function (Request $request) {
                $request->user()->hasPermissionTo(PermissionsEnum::viewAny_project->name);
            }),

        ];
    }
}
