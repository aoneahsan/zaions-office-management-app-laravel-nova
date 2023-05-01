<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function zTestingRouteRes(Request $request)
    {
        $user = $request->user();
        return dd($user->roles()->pluck('name'), $user->hasRole(RolesEnum::superAdmin->name));
    }
}
