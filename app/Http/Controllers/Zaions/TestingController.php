<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use App\Models\Default\WorkSpace;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function zTestingRouteRes(Request $request)
    {
        // check user Role Test
        $this->checkUserRoleTest($request);

        return response()->json('working fine');
    }

    public function checkUserWorkSpacePixelTest(Request $request)
    {
        // Test check if user is super admin
        // $user = $request->user();
        // dd($user->roles()->pluck('name'), $user->hasRole(RolesEnum::superAdmin->name));

        // Test - working with Carbon date and time
        // $carbonNow = Carbon::now($request->user()?->userTimezone);
        // $carbonNow = Carbon::now();
        // $dateInfo = [
        //     '$carbonNow' => $carbonNow,
        //     '$carbonNow->hour' => $carbonNow->hour,
        //     '$carbonNow->minute' => $carbonNow->minute,
        //     '$carbonNow->month' => $carbonNow->month,
        //     '$carbonNow->weekOfYear' => $carbonNow->weekOfYear,
        //     '$carbonNow->day' => $carbonNow->day,
        //     '$carbonNow->dayOfWeek' => $carbonNow->dayOfWeek,
        //     '$carbonNow->dayName' => $carbonNow->dayName,
        //     '12 hour format' => ZHelpers::convertTo12Hour($carbonNow)
        // ];

        // dd($dateInfo, $request->user()?->userTimezone);

        // $workspace = WorkSpace::with('pixel')->where('userId', 1)->where(
        //     'uniqueId',
        //     '64737b6d45f34'
        // )->first();

        // dd($workspace, $workspace->pixel);
    }

    public function checkUserRoleTest(Request $request)
    {
        $user = $request->user();

        $roles = $user->roles();
        $role = $roles->first();
        $roleName = optional($role)->name;

        dd($user, $roles, $role, $roleName);
    }
}
