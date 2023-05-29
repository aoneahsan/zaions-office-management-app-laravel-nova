<?php

namespace Database\Seeders\Default;

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

        // workspace member roles
        $wsContributor = Role::create(['name' => RolesEnum::ws_contributor->name]);
        $wsAdministrator = Role::create(['name' => RolesEnum::ws_administrator->name]);
        $wsWriter = Role::create(['name' => RolesEnum::ws_writer->name]);
        $wsApprover = Role::create(['name' => RolesEnum::ws_approver->name]);
        $wsGuest = Role::create(['name' => RolesEnum::ws_guest->name]);


        // All App Permissions
        // Dashboard Permissions
        $viewDashboardPermission = Permission::create(['name' => PermissionsEnum::view_dashboard->name]);
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
        $replicateRolePermission = Permission::create(['name' => PermissionsEnum::replicate_role->name]);
        $restoreRolePermission = Permission::create(['name' => PermissionsEnum::restore_role->name]);
        $forceDeleteRolePermission = Permission::create(['name' => PermissionsEnum::forceDelete_role->name]);
        // Permissions Model Permissions
        $viewAnyPermissionPermission = Permission::create(['name' => PermissionsEnum::viewAny_permission->name]);
        $viewPermissionPermission = Permission::create(['name' => PermissionsEnum::view_permission->name]);
        $addPermissionPermission = Permission::create(['name' => PermissionsEnum::create_permission->name]);
        $updatePermissionPermission = Permission::create(['name' => PermissionsEnum::update_permission->name]);
        $deletePermissionPermission = Permission::create(['name' => PermissionsEnum::delete_permission->name]);
        $replicatePermissionPermission = Permission::create(['name' => PermissionsEnum::replicate_permission->name]);
        $restorePermissionPermission = Permission::create(['name' => PermissionsEnum::restore_permission->name]);
        $forceDeletePermissionPermission = Permission::create(['name' => PermissionsEnum::forceDelete_permission->name]);
        // Tasks Model Permissions
        $viewAnyTaskPermission = Permission::create(['name' => PermissionsEnum::viewAny_task->name]);
        $viewTaskPermission = Permission::create(['name' => PermissionsEnum::view_task->name]);
        $addTaskPermission = Permission::create(['name' => PermissionsEnum::create_task->name]);
        $updateTaskPermission = Permission::create(['name' => PermissionsEnum::update_task->name]);
        $deleteTaskPermission = Permission::create(['name' => PermissionsEnum::delete_task->name]);
        $replicateTaskPermission = Permission::create(['name' => PermissionsEnum::replicate_task->name]);
        $restoreTaskPermission = Permission::create(['name' => PermissionsEnum::restore_task->name]);
        $forceDeleteTaskPermission = Permission::create(['name' => PermissionsEnum::forceDelete_task->name]);
        // History Model Permissions
        $viewAnyHistoryPermission = Permission::create(['name' => PermissionsEnum::viewAny_history->name]);
        $viewHistoryPermission = Permission::create(['name' => PermissionsEnum::view_history->name]);
        $addHistoryPermission = Permission::create(['name' => PermissionsEnum::create_history->name]);
        $updateHistoryPermission = Permission::create(['name' => PermissionsEnum::update_history->name]);
        $deleteHistoryPermission = Permission::create(['name' => PermissionsEnum::delete_history->name]);
        $replicateHistoryPermission = Permission::create(['name' => PermissionsEnum::replicate_history->name]);
        $restoreHistoryPermission = Permission::create(['name' => PermissionsEnum::restore_history->name]);
        $forceDeleteHistoryPermission = Permission::create(['name' => PermissionsEnum::forceDelete_history->name]);
        // Attachments Model Permissions
        $viewAnyAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewAny_attachment->name]);
        $viewAttachmentPermission = Permission::create(['name' => PermissionsEnum::view_attachment->name]);
        $addAttachmentPermission = Permission::create(['name' => PermissionsEnum::create_attachment->name]);
        $updateAttachmentPermission = Permission::create(['name' => PermissionsEnum::update_attachment->name]);
        $deleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::delete_attachment->name]);
        $replicateAttachmentPermission = Permission::create(['name' => PermissionsEnum::replicate_attachment->name]);
        $restoreAttachmentPermission = Permission::create(['name' => PermissionsEnum::restore_attachment->name]);
        $forceDeleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_attachment->name]);
        // Comments Model Permissions
        $viewAnyCommentPermission = Permission::create(['name' => PermissionsEnum::viewAny_comment->name]);
        $viewCommentPermission = Permission::create(['name' => PermissionsEnum::view_comment->name]);
        $addCommentPermission = Permission::create(['name' => PermissionsEnum::create_comment->name]);
        $updateCommentPermission = Permission::create(['name' => PermissionsEnum::update_comment->name]);
        $deleteCommentPermission = Permission::create(['name' => PermissionsEnum::delete_comment->name]);
        $replicateCommentPermission = Permission::create(['name' => PermissionsEnum::replicate_comment->name]);
        $restoreCommentPermission = Permission::create(['name' => PermissionsEnum::restore_comment->name]);
        $forceDeleteCommentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_comment->name]);
        // Replies Model Permissions
        $viewAnyReplyPermission = Permission::create(['name' => PermissionsEnum::viewAny_reply->name]);
        $viewReplyPermission = Permission::create(['name' => PermissionsEnum::view_reply->name]);
        $addReplyPermission = Permission::create(['name' => PermissionsEnum::create_reply->name]);
        $updateReplyPermission = Permission::create(['name' => PermissionsEnum::update_reply->name]);
        $deleteReplyPermission = Permission::create(['name' => PermissionsEnum::delete_reply->name]);
        $replicateReplyPermission = Permission::create(['name' => PermissionsEnum::replicate_reply->name]);
        $restoreReplyPermission = Permission::create(['name' => PermissionsEnum::restore_reply->name]);
        $forceDeleteReplyPermission = Permission::create(['name' => PermissionsEnum::forceDelete_reply->name]);
        // Impersonation Permissions
        $canImpersonatePermission = Permission::create(['name' => PermissionsEnum::can_impersonate->name]);
        $canBeImpersonatePermission = Permission::create(['name' => PermissionsEnum::canBe_impersonate->name]);

        $superAdminRolePermissions = [
            // Dashboard
            $viewDashboardPermission,
            // User
            $viewAnyUserPermission,
            $viewUserPermission,
            $addUserPermission,
            $updateUserPermission,
            $deleteUserPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            // Role
            $viewAnyRolePermission,
            $viewRolePermission,
            $addRolePermission,
            $updateRolePermission,
            $deleteRolePermission,
            $replicateRolePermission,
            $restoreRolePermission,
            $forceDeleteRolePermission,
            // Permission
            $viewAnyPermissionPermission,
            $viewPermissionPermission,
            $addPermissionPermission,
            $updatePermissionPermission,
            $deletePermissionPermission,
            $replicatePermissionPermission,
            $restorePermissionPermission,
            $forceDeletePermissionPermission,
            // Task
            $viewAnyTaskPermission,
            $viewTaskPermission,
            $addTaskPermission,
            $updateTaskPermission,
            $deleteTaskPermission,
            $replicateTaskPermission,
            $restoreTaskPermission,
            $forceDeleteTaskPermission,
            // History
            $viewAnyHistoryPermission,
            $viewHistoryPermission,
            $addHistoryPermission,
            $updateHistoryPermission,
            $deleteHistoryPermission,
            $replicateHistoryPermission,
            $restoreHistoryPermission,
            $forceDeleteHistoryPermission,
            // Attachment
            $viewAnyAttachmentPermission,
            $viewAttachmentPermission,
            $addAttachmentPermission,
            $updateAttachmentPermission,
            $deleteAttachmentPermission,
            $replicateAttachmentPermission,
            $restoreAttachmentPermission,
            $forceDeleteAttachmentPermission,
            // Comment
            $viewAnyCommentPermission,
            $viewCommentPermission,
            $addCommentPermission,
            $updateCommentPermission,
            $deleteCommentPermission,
            $replicateCommentPermission,
            $restoreCommentPermission,
            $forceDeleteCommentPermission,
            // Reply
            $viewAnyReplyPermission,
            $viewReplyPermission,
            $addReplyPermission,
            $updateReplyPermission,
            $deleteReplyPermission,
            $replicateReplyPermission,
            $restoreReplyPermission,
            $forceDeleteReplyPermission,
            // Impersonation
            $canImpersonatePermission,
            // $canBeImpersonatePermission // this is commented by ahsan, as no one in app should impersonate super admin user account
        ];

        $adminRolePermissions = array_filter($superAdminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('forceDelete_');
        });

        // add canBeImpersonatePermission Permission
        array_push($adminRolePermissions, $canBeImpersonatePermission);

        $userRolePermissions = array_filter($adminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('delete_') && !Str::of($permission->name)->contains('update_') && !Str::of($permission->name)->contains('_user') && !Str::of($permission->name)->contains('_role') && !Str::of($permission->name)->contains('_permission') && !Str::of($permission->name)->contains('Impersonate_');
        });

        // Assign permissions to roles
        $superAdminRole->syncPermissions($superAdminRolePermissions);
        $adminRole->syncPermissions($adminRolePermissions);
        $userRole->syncPermissions($userRolePermissions);
    }
}
