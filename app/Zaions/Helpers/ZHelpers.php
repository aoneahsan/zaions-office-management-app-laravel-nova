<?php

namespace App\Zaions\Helpers;

use App\Zaions\Enums\RolesEnum;
use Laravel\Nova\Http\Requests\NovaRequest;

class ZHelpers
{
  static public function isNRUserSuperAdmin(NovaRequest $request)
  {
    return $request->user()->hasRole(RolesEnum::superAdmin->name);
  }
}
