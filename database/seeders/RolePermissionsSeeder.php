<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\RolesEnum;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Roles
        $superAdminRole = Role::create(['name' => RolesEnum::superAdmin->name]);
        $adminRole = Role::create(['name' => RolesEnum::admin->name]);
        $userRole = Role::create(['name' => RolesEnum::user->name]);

        // All App Permissions
        // Users Model Permissions
        $viewAnyUserPermission = Permission::create(['name' => PermissionsEnum::viewAny_user->name]);
        $viewUserPermission = Permission::create(['name' => PermissionsEnum::view_user->name]);
        $addUserPermission = Permission::create(['name' => PermissionsEnum::create_user->name]);
        $updateUserPermission = Permission::create(['name' => PermissionsEnum::update_user->name]);
        $deleteUserPermission = Permission::create(['name' => PermissionsEnum::delete_user->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_user->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_user->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_user->name]);
        // Roles Model Permissions
        $viewAnyRolePermission = Permission::create(['name' => PermissionsEnum::viewAny_role->name]);
        $viewRolePermission = Permission::create(['name' => PermissionsEnum::view_role->name]);
        $addRolePermission = Permission::create(['name' => PermissionsEnum::create_role->name]);
        $updateRolePermission = Permission::create(['name' => PermissionsEnum::update_role->name]);
        $deleteRolePermission = Permission::create(['name' => PermissionsEnum::delete_role->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_role->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_role->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_role->name]);
        // Permissions Model Permissions
        $viewAnyPermissionPermission = Permission::create(['name' => PermissionsEnum::viewAny_permission->name]);
        $viewPermissionPermission = Permission::create(['name' => PermissionsEnum::view_permission->name]);
        $addPermissionPermission = Permission::create(['name' => PermissionsEnum::create_permission->name]);
        $updatePermissionPermission = Permission::create(['name' => PermissionsEnum::update_permission->name]);
        $deletePermissionPermission = Permission::create(['name' => PermissionsEnum::delete_permission->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_permission->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_permission->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_permission->name]);
        // Tasks Model Permissions
        $viewAnyTaskPermission = Permission::create(['name' => PermissionsEnum::viewAny_task->name]);
        $viewTaskPermission = Permission::create(['name' => PermissionsEnum::view_task->name]);
        $addTaskPermission = Permission::create(['name' => PermissionsEnum::create_task->name]);
        $updateTaskPermission = Permission::create(['name' => PermissionsEnum::update_task->name]);
        $deleteTaskPermission = Permission::create(['name' => PermissionsEnum::delete_task->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_task->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_task->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_task->name]);
        // History Model Permissions
        $viewAnyHistoryPermission = Permission::create(['name' => PermissionsEnum::viewAny_history->name]);
        $viewHistoryPermission = Permission::create(['name' => PermissionsEnum::view_history->name]);
        $addHistoryPermission = Permission::create(['name' => PermissionsEnum::create_history->name]);
        $updateHistoryPermission = Permission::create(['name' => PermissionsEnum::update_history->name]);
        $deleteHistoryPermission = Permission::create(['name' => PermissionsEnum::delete_history->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_history->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_history->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_history->name]);
        // Attachments Model Permissions
        $viewAnyAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewAny_attachment->name]);
        $viewAttachmentPermission = Permission::create(['name' => PermissionsEnum::view_attachment->name]);
        $addAttachmentPermission = Permission::create(['name' => PermissionsEnum::create_attachment->name]);
        $updateAttachmentPermission = Permission::create(['name' => PermissionsEnum::update_attachment->name]);
        $deleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::delete_attachment->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_attachment->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_attachment->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_attachment->name]);
        // Comments Model Permissions
        $viewAnyCommentPermission = Permission::create(['name' => PermissionsEnum::viewAny_comment->name]);
        $viewCommentPermission = Permission::create(['name' => PermissionsEnum::view_comment->name]);
        $addCommentPermission = Permission::create(['name' => PermissionsEnum::create_comment->name]);
        $updateCommentPermission = Permission::create(['name' => PermissionsEnum::update_comment->name]);
        $deleteCommentPermission = Permission::create(['name' => PermissionsEnum::delete_comment->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_comment->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_comment->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_comment->name]);

        $superAdminRolePermissions = [
            $viewAnyUserPermission,
            $viewUserPermission,
            $addUserPermission,
            $updateUserPermission,
            $deleteUserPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyRolePermission,
            $viewRolePermission,
            $addRolePermission,
            $updateRolePermission,
            $deleteRolePermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyPermissionPermission,
            $viewPermissionPermission,
            $addPermissionPermission,
            $updatePermissionPermission,
            $deletePermissionPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyTaskPermission,
            $viewTaskPermission,
            $addTaskPermission,
            $updateTaskPermission,
            $deleteTaskPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyHistoryPermission,
            $viewHistoryPermission,
            $addHistoryPermission,
            $updateHistoryPermission,
            $deleteHistoryPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyAttachmentPermission,
            $viewAttachmentPermission,
            $addAttachmentPermission,
            $updateAttachmentPermission,
            $deleteAttachmentPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            $viewAnyCommentPermission,
            $viewCommentPermission,
            $addCommentPermission,
            $updateCommentPermission,
            $deleteCommentPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission
        ];

        $adminRolePermissions = array_filter($superAdminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('forceDelete_');
        });

        $userRolePermissions = array_filter($adminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('delete_') && !Str::of($permission->name)->contains('update_') && !Str::of($permission->name)->contains('_user') && !Str::of($permission->name)->contains('_role') && !Str::of($permission->name)->contains('_permission');
        });

        // Assign permissions to roles
        $superAdminRole->syncPermissions($superAdminRolePermissions);
        $adminRole->syncPermissions($adminRolePermissions);
        $userRole->syncPermissions($userRolePermissions);
    }
}
