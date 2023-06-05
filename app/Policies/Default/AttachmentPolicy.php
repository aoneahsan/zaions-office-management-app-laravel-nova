<?php

namespace App\Policies\Default;

use App\Models\Default\Attachment;
use App\Models\User;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_attachment->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_attachment->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_attachment->name);
    }

    public function update(User $user, Attachment $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::update_attachment->name);
    }

    public function replicate(User $user)
    {
        // return $user->hasPermissionTo(PermissionsEnum::replicate_attachment->name);
        return false;
    }

    public function delete(User $user, Attachment $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::delete_attachment->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_attachment->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_attachment->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_attachment->name) && $user->hasPermissionTo(PermissionsEnum::update_attachment->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_attachment->name) && $user->hasPermissionTo(PermissionsEnum::delete_attachment->name);
    }
}
