<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use App\Models\ZTestingDemo;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    public function viewAny(User $user)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::viewAny_task->name);
        }
    }

    public function view(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::view_task->name) && $model->userId === $user->id;
        }
    }

    public function create(User $user)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::create_task->name);
        }
    }

    public function update(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::update_task->name) && $model->userId === $user->id;
        }
    }

    public function replicate(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::replicate_task->name) && $model->userId === $user->id;
        }
    }

    public function delete(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::delete_task->name) && $model->userId === $user->id;
        }
    }

    public function restore(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::restore_task->name) && $model->userId === $user->id;
        }
    }

    public function forceDelete(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::forceDelete_task->name) && $model->userId === $user->id;
        }
    }

    public function runAction(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::create_task->name) && $user->hasPermissionTo(PermissionsEnum::update_task->name) && $model->userId === $user->id;
        }
    }

    public function runDestructiveAction(User $user, Note $model)
    {
        if (ZHelpers::isSuperUser($user)) {
            return true;
        } else {
            return $user->hasPermissionTo(PermissionsEnum::update_task->name) && $user->hasPermissionTo(PermissionsEnum::delete_task->name) && $model->userId === $user->id;
        }
    }
}
