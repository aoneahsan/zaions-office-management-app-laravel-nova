<?php

namespace App\Policies\Default;

use App\Models\Default\Reply;
use App\Models\User;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_reply->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_reply->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_reply->name);
    }

    public function update(User $user, Reply $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::update_reply->name);
    }

    public function replicate(User $user)
    {
        // return $user->hasPermissionTo(PermissionsEnum::replicate_reply->name);
        return false;
    }

    public function delete(User $user, Reply $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::delete_reply->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_reply->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_reply->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_reply->name) && $user->hasPermissionTo(PermissionsEnum::update_reply->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_reply->name) && $user->hasPermissionTo(PermissionsEnum::delete_reply->name);
    }
}
