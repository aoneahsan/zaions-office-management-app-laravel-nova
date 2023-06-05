<?php

namespace App\Policies\FPI;

use App\Models\FPI\ProjectTransaction;
use App\Models\User;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_projectTransaction->name);
    }

    public function view(User $user, ProjectTransaction $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::view_projectTransaction->name);
    }

    public function create(User $user)
    {
        // return $user->hasPermissionTo(PermissionsEnum::create_projectTransaction->name);
        return false;
    }

    public function update(User $user, ProjectTransaction $model)
    {
        // return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::update_projectTransaction->name);
        return false;
    }

    public function replicate(User $user)
    {
        // return $user->hasPermissionTo(PermissionsEnum::replicate_projectTransaction->name);
        return false;
    }

    public function delete(User $user, ProjectTransaction $model)
    {
        // return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::delete_projectTransaction->name);
        return false;
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_projectTransaction->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_projectTransaction->name);
    }

    public function runAction(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_projectTransaction->name) && $user->hasPermissionTo(PermissionsEnum::update_projectTransaction->name);
    }

    public function runDestructiveAction(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_projectTransaction->name) && $user->hasPermissionTo(PermissionsEnum::delete_projectTransaction->name);
    }
}
