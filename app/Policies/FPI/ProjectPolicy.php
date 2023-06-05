<?php

namespace App\Policies\FPI;

use App\Models\FPI\Project;
use App\Models\User;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{

    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_project->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_project->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_project->name);
    }

    public function update(User $user, Project $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::update_project->name);
    }

    public function replicate(User $user)
    {
        // return $user->hasPermissionTo(PermissionsEnum::replicate_project->name);
        return false;
    }

    public function delete(User $user, Project $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId) && $user->hasPermissionTo(PermissionsEnum::delete_project->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_project->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_project->name);
    }

    public function runAction(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_project->name) && $user->hasPermissionTo(PermissionsEnum::update_project->name);
    }

    public function runDestructiveAction(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_project->name) && $user->hasPermissionTo(PermissionsEnum::delete_project->name);
    }

    public function addAttachment(User $user, Project $model)
    {
        return ZHelpers::isAdminLevelUserOrOwner($user, $model->userId);
    }
}
