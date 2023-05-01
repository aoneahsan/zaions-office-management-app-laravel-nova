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
        $viewAllUserPermission = Permission::create(['name' => PermissionsEnum::viewAll_user->name]);
        $viewUserPermission = Permission::create(['name' => PermissionsEnum::view_user->name]);
        $addUserPermission = Permission::create(['name' => PermissionsEnum::add_user->name]);
        $editUserPermission = Permission::create(['name' => PermissionsEnum::edit_user->name]);
        $deleteUserPermission = Permission::create(['name' => PermissionsEnum::delete_user->name]);
        // Roles Model Permissions
        $viewAllRolePermission = Permission::create(['name' => PermissionsEnum::viewAll_role->name]);
        $viewRolePermission = Permission::create(['name' => PermissionsEnum::view_role->name]);
        $addRolePermission = Permission::create(['name' => PermissionsEnum::add_role->name]);
        $editRolePermission = Permission::create(['name' => PermissionsEnum::edit_role->name]);
        $deleteRolePermission = Permission::create(['name' => PermissionsEnum::delete_role->name]);
        // Permissions Model Permissions
        $viewAllPermissionPermission = Permission::create(['name' => PermissionsEnum::viewAll_permission->name]);
        $viewPermissionPermission = Permission::create(['name' => PermissionsEnum::view_permission->name]);
        $addPermissionPermission = Permission::create(['name' => PermissionsEnum::add_permission->name]);
        $editPermissionPermission = Permission::create(['name' => PermissionsEnum::edit_permission->name]);
        $deletePermissionPermission = Permission::create(['name' => PermissionsEnum::delete_permission->name]);
        // Tasks Model Permissions
        $viewAllTaskPermission = Permission::create(['name' => PermissionsEnum::viewAll_task->name]);
        $viewTaskPermission = Permission::create(['name' => PermissionsEnum::view_task->name]);
        $addTaskPermission = Permission::create(['name' => PermissionsEnum::add_task->name]);
        $editTaskPermission = Permission::create(['name' => PermissionsEnum::edit_task->name]);
        $deleteTaskPermission = Permission::create(['name' => PermissionsEnum::delete_task->name]);
        // History Model Permissions
        $viewAllHistoryPermission = Permission::create(['name' => PermissionsEnum::viewAll_history->name]);
        $viewHistoryPermission = Permission::create(['name' => PermissionsEnum::view_history->name]);
        $addHistoryPermission = Permission::create(['name' => PermissionsEnum::add_history->name]);
        $editHistoryPermission = Permission::create(['name' => PermissionsEnum::edit_history->name]);
        $deleteHistoryPermission = Permission::create(['name' => PermissionsEnum::delete_history->name]);
        // Attachments Model Permissions
        $viewAllAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewAll_attachment->name]);
        $viewAttachmentPermission = Permission::create(['name' => PermissionsEnum::view_attachment->name]);
        $addAttachmentPermission = Permission::create(['name' => PermissionsEnum::add_attachment->name]);
        $editAttachmentPermission = Permission::create(['name' => PermissionsEnum::edit_attachment->name]);
        $deleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::delete_attachment->name]);
        // Comments Model Permissions
        $viewAllCommentPermission = Permission::create(['name' => PermissionsEnum::viewAll_comment->name]);
        $viewCommentPermission = Permission::create(['name' => PermissionsEnum::view_comment->name]);
        $addCommentPermission = Permission::create(['name' => PermissionsEnum::add_comment->name]);
        $editCommentPermission = Permission::create(['name' => PermissionsEnum::edit_comment->name]);
        $deleteCommentPermission = Permission::create(['name' => PermissionsEnum::delete_comment->name]);

        $superAdminRolePermissions = [
            $viewAllUserPermission,
            $viewUserPermission,
            $addUserPermission,
            $editUserPermission,
            $deleteUserPermission,
            $viewAllRolePermission,
            $viewRolePermission,
            $addRolePermission,
            $editRolePermission,
            $deleteRolePermission,
            $viewAllPermissionPermission,
            $viewPermissionPermission,
            $addPermissionPermission,
            $editPermissionPermission,
            $deletePermissionPermission,
            $viewAllTaskPermission,
            $viewTaskPermission,
            $addTaskPermission,
            $editTaskPermission,
            $deleteTaskPermission,
            $viewAllHistoryPermission,
            $viewHistoryPermission,
            $addHistoryPermission,
            $editHistoryPermission,
            $deleteHistoryPermission,
            $viewAllAttachmentPermission,
            $viewAttachmentPermission,
            $addAttachmentPermission,
            $editAttachmentPermission,
            $deleteAttachmentPermission,
            $viewAllCommentPermission,
            $viewCommentPermission,
            $addCommentPermission,
            $editCommentPermission,
            $deleteCommentPermission
        ];

        $adminRolePermissions = array_filter($superAdminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('delete_');
        });

        $userRolePermissions = array_filter($adminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('edit_') && !Str::of($permission->name)->contains('_user') && !Str::of($permission->name)->contains('_role') && !Str::of($permission->name)->contains('_permission');
        });

        // Assign permissions to roles
        $superAdminRole->syncPermissions($superAdminRolePermissions);
        $adminRole->syncPermissions($adminRolePermissions);
        $userRole->syncPermissions($userRolePermissions);
    }
}
