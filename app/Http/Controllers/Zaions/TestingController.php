<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function zTestingRouteRes(Request $request)
    {
        // Test check if user is super admin
        // $user = $request->user();
        // dd($user->roles()->pluck('name'), $user->hasRole(RolesEnum::superAdmin->name));

        // Test - working with Carbon date and time
        // $carbonNow = Carbon::now($request->user()?->userTimezone);
        $carbonNow = Carbon::now();
        $dateInfo = [
            '$carbonNow' => $carbonNow,
            '$carbonNow->hour' => $carbonNow->hour,
            '$carbonNow->minute' => $carbonNow->minute,
            '$carbonNow->month' => $carbonNow->month,
            '$carbonNow->weekOfYear' => $carbonNow->weekOfYear,
            '$carbonNow->day' => $carbonNow->day,
            '$carbonNow->dayOfWeek' => $carbonNow->dayOfWeek,
            '$carbonNow->dayName' => $carbonNow->dayName,
            '12 hour format' => ZHelpers::convertTo12Hour($carbonNow)
        ];

        dd($dateInfo, $request->user()?->userTimezone);


        return response()->json('working fine');
    }
}
