<?php

namespace Database\Seeders\Default;

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
        $simpleUserRole = Role::create(['name' => RolesEnum::simpleUser->name]);
        $brokerRole = Role::create(['name' => RolesEnum::broker->name]);
        $developerRole = Role::create(['name' => RolesEnum::developer->name]);
        $investorRole = Role::create(['name' => RolesEnum::investor->name]);


        // All App Permissions
        // Roles Model Permissions
        $viewAnyRolePermission = Permission::create(['name' => PermissionsEnum::viewAny_role->name]);
        $viewRolePermission = Permission::create(['name' => PermissionsEnum::view_role->name]);
        $addRolePermission = Permission::create(['name' => PermissionsEnum::create_role->name]);
        $updateRolePermission = Permission::create(['name' => PermissionsEnum::update_role->name]);
        $deleteRolePermission = Permission::create(['name' => PermissionsEnum::delete_role->name]);
        $replicateRolePermission = Permission::create(['name' => PermissionsEnum::replicate_role->name]);
        $restoreRolePermission = Permission::create(['name' => PermissionsEnum::restore_role->name]);
        $forceDeleteRolePermission = Permission::create(['name' => PermissionsEnum::forceDelete_role->name]);
        $viewResourceRolePermission = Permission::create(['name' => PermissionsEnum::viewResource_role->name]);
        $runRoleActionPermission = Permission::create(['name' => PermissionsEnum::run_roleAction->name]);
        $runRoleDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_roleDestructiveAction->name]);
        // Permissions Model Permissions
        $viewAnyPermissionPermission = Permission::create(['name' => PermissionsEnum::viewAny_permission->name]);
        $viewPermissionPermission = Permission::create(['name' => PermissionsEnum::view_permission->name]);
        $addPermissionPermission = Permission::create(['name' => PermissionsEnum::create_permission->name]);
        $updatePermissionPermission = Permission::create(['name' => PermissionsEnum::update_permission->name]);
        $deletePermissionPermission = Permission::create(['name' => PermissionsEnum::delete_permission->name]);
        $replicatePermissionPermission = Permission::create(['name' => PermissionsEnum::replicate_permission->name]);
        $restorePermissionPermission = Permission::create(['name' => PermissionsEnum::restore_permission->name]);
        $forceDeletePermissionPermission = Permission::create(['name' => PermissionsEnum::forceDelete_permission->name]);
        $viewResourcePermissionPermission = Permission::create(['name' => PermissionsEnum::viewResource_permission->name]);
        $runPermissionActionPermission = Permission::create(['name' => PermissionsEnum::run_permissionAction->name]);
        $runPermissionDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_permissionDestructiveAction->name]);
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
        $viewResourceUserPermission = Permission::create(['name' => PermissionsEnum::viewResource_user->name]);
        $runUserActionPermission = Permission::create(['name' => PermissionsEnum::run_userAction->name]);
        $runUserDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_userDestructiveAction->name]);
        // Attachments Model Permissions
        $viewAnyAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewAny_attachment->name]);
        $viewAttachmentPermission = Permission::create(['name' => PermissionsEnum::view_attachment->name]);
        $addAttachmentPermission = Permission::create(['name' => PermissionsEnum::create_attachment->name]);
        $updateAttachmentPermission = Permission::create(['name' => PermissionsEnum::update_attachment->name]);
        $deleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::delete_attachment->name]);
        $replicateAttachmentPermission = Permission::create(['name' => PermissionsEnum::replicate_attachment->name]);
        $restoreAttachmentPermission = Permission::create(['name' => PermissionsEnum::restore_attachment->name]);
        $forceDeleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_attachment->name]);
        $viewResourceAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewResource_attachment->name]);
        $runAttachmentActionPermission = Permission::create(['name' => PermissionsEnum::run_attachmentAction->name]);
        $runAttachmentDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_attachmentDestructiveAction->name]);
        // Comments Model Permissions
        $viewAnyCommentPermission = Permission::create(['name' => PermissionsEnum::viewAny_comment->name]);
        $viewCommentPermission = Permission::create(['name' => PermissionsEnum::view_comment->name]);
        $addCommentPermission = Permission::create(['name' => PermissionsEnum::create_comment->name]);
        $updateCommentPermission = Permission::create(['name' => PermissionsEnum::update_comment->name]);
        $deleteCommentPermission = Permission::create(['name' => PermissionsEnum::delete_comment->name]);
        $replicateCommentPermission = Permission::create(['name' => PermissionsEnum::replicate_comment->name]);
        $restoreCommentPermission = Permission::create(['name' => PermissionsEnum::restore_comment->name]);
        $forceDeleteCommentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_comment->name]);
        $viewResourceCommentPermission = Permission::create(['name' => PermissionsEnum::viewResource_comment->name]);
        $runCommentActionPermission = Permission::create(['name' => PermissionsEnum::run_commentAction->name]);
        $runCommentDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_commentDestructiveAction->name]);
        // Replies Model Permissions
        $viewAnyReplyPermission = Permission::create(['name' => PermissionsEnum::viewAny_reply->name]);
        $viewReplyPermission = Permission::create(['name' => PermissionsEnum::view_reply->name]);
        $addReplyPermission = Permission::create(['name' => PermissionsEnum::create_reply->name]);
        $updateReplyPermission = Permission::create(['name' => PermissionsEnum::update_reply->name]);
        $deleteReplyPermission = Permission::create(['name' => PermissionsEnum::delete_reply->name]);
        $replicateReplyPermission = Permission::create(['name' => PermissionsEnum::replicate_reply->name]);
        $restoreReplyPermission = Permission::create(['name' => PermissionsEnum::restore_reply->name]);
        $forceDeleteReplyPermission = Permission::create(['name' => PermissionsEnum::forceDelete_reply->name]);
        $viewResourceReplyPermission = Permission::create(['name' => PermissionsEnum::viewResource_reply->name]);
        $runReplyActionPermission = Permission::create(['name' => PermissionsEnum::run_replyAction->name]);
        $runReplyDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_replyDestructiveAction->name]);
        // Impersonation Permissions
        $canImpersonatePermission = Permission::create(['name' => PermissionsEnum::can_impersonate->name]);
        $canBeImpersonatePermission = Permission::create(['name' => PermissionsEnum::canBe_impersonate->name]);
        // Projects Model Permissions
        $viewAnyProjectPermission = Permission::create(['name' => PermissionsEnum::viewAny_project->name]);
        $viewProjectPermission = Permission::create(['name' => PermissionsEnum::view_project->name]);
        $addProjectPermission = Permission::create(['name' => PermissionsEnum::create_project->name]);
        $updateProjectPermission = Permission::create(['name' => PermissionsEnum::update_project->name]);
        $deleteProjectPermission = Permission::create(['name' => PermissionsEnum::delete_project->name]);
        $replicateProjectPermission = Permission::create(['name' => PermissionsEnum::replicate_project->name]);
        $restoreProjectPermission = Permission::create(['name' => PermissionsEnum::restore_project->name]);
        $forceDeleteProjectPermission = Permission::create(['name' => PermissionsEnum::forceDelete_project->name]);
        $viewResourceProjectPermission = Permission::create(['name' => PermissionsEnum::viewResource_project->name]);
        $viewLensProjectRebateListPagePermission = Permission::create(['name' => PermissionsEnum::viewLens_projectRebateListPage->name]);
        $runProjectActionPermission = Permission::create(['name' => PermissionsEnum::run_projectAction->name]);
        $runProjectDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_projectDestructiveAction->name]);
        // Profile Permissions
        $viewProfilePermission = Permission::create(['name' => PermissionsEnum::view_profile->name]);
        $updateProfilePermission = Permission::create(['name' => PermissionsEnum::update_profile->name]);
        $deleteProfilePermission = Permission::create(['name' => PermissionsEnum::delete_profile->name]);
        // 2FA Permissions
        $view2FAPermission = Permission::create(['name' => PermissionsEnum::view_2fa->name]);
        $create2FAPermission = Permission::create(['name' => PermissionsEnum::create_2fa->name]);
        $update2FAPermission = Permission::create(['name' => PermissionsEnum::update_2fa->name]);
        $delete2FAPermission = Permission::create(['name' => PermissionsEnum::delete_2fa->name]);
        // ProjectTransaction Model Permissions
        $viewAnyProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::viewAny_projectTransaction->name]);
        $viewProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::view_projectTransaction->name]);
        $addProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::create_projectTransaction->name]);
        $updateProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::update_projectTransaction->name]);
        $deleteProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::delete_projectTransaction->name]);
        $replicateProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::replicate_projectTransaction->name]);
        $restoreProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::restore_projectTransaction->name]);
        $forceDeleteProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::forceDelete_projectTransaction->name]);
        $viewResourceProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::viewResource_projectTransaction->name]);
        $viewLensPersonalProjectTransactionPermission = Permission::create(['name' => PermissionsEnum::viewLens_personalProjectTransactionLens->name]);
        $runProjectTransactionActionPermission = Permission::create(['name' => PermissionsEnum::run_projectTransactionAction->name]);
        $runProjectTransactionDestructiveActionPermission = Permission::create(['name' => PermissionsEnum::run_projectTransactionDestructiveAction->name]);

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
            $viewResourceUserPermission,
            $runUserActionPermission,
            $runUserDestructiveActionPermission,
            // Role
            $viewAnyRolePermission,
            $viewRolePermission,
            $addRolePermission,
            $updateRolePermission,
            $deleteRolePermission,
            $replicateRolePermission,
            $restoreRolePermission,
            $forceDeleteRolePermission,
            $viewResourceRolePermission,
            $runRoleActionPermission,
            $runRoleDestructiveActionPermission,
            // Permission
            $viewAnyPermissionPermission,
            $viewPermissionPermission,
            $addPermissionPermission,
            $updatePermissionPermission,
            $deletePermissionPermission,
            $replicatePermissionPermission,
            $restorePermissionPermission,
            $forceDeletePermissionPermission,
            $viewResourcePermissionPermission,
            $runPermissionActionPermission,
            $runPermissionDestructiveActionPermission,
            // Attachment
            $viewAnyAttachmentPermission,
            $viewAttachmentPermission,
            $addAttachmentPermission,
            $updateAttachmentPermission,
            $deleteAttachmentPermission,
            $replicateAttachmentPermission,
            $restoreAttachmentPermission,
            $forceDeleteAttachmentPermission,
            $viewResourceAttachmentPermission,
            $runAttachmentActionPermission,
            $runAttachmentDestructiveActionPermission,
            // Comment
            $viewAnyCommentPermission,
            $viewCommentPermission,
            $addCommentPermission,
            $updateCommentPermission,
            $deleteCommentPermission,
            $replicateCommentPermission,
            $restoreCommentPermission,
            $forceDeleteCommentPermission,
            $viewResourceCommentPermission,
            $runCommentActionPermission,
            $runCommentDestructiveActionPermission,
            // Reply
            $viewAnyReplyPermission,
            $viewReplyPermission,
            $addReplyPermission,
            $updateReplyPermission,
            $deleteReplyPermission,
            $replicateReplyPermission,
            $restoreReplyPermission,
            $forceDeleteReplyPermission,
            $viewResourceReplyPermission,
            $runReplyActionPermission,
            $runReplyDestructiveActionPermission,
            // Impersonation
            $canImpersonatePermission,
            // $canBeImpersonatePermission // this is commented by ahsan, as no one in app should impersonate super admin user account
            // Project
            $viewAnyProjectPermission,
            $viewProjectPermission,
            $addProjectPermission,
            $updateProjectPermission,
            $deleteProjectPermission,
            $replicateProjectPermission,
            $restoreProjectPermission,
            $forceDeleteProjectPermission,
            $viewResourceProjectPermission,
            $viewLensProjectRebateListPagePermission,
            $runProjectActionPermission,
            $runProjectDestructiveActionPermission,
            // Profile
            $viewProfilePermission,
            $updateProfilePermission,
            $deleteProfilePermission,
            // 2FA
            $view2FAPermission,
            $create2FAPermission,
            $update2FAPermission,
            $delete2FAPermission,
            // ProjectTransaction
            $viewAnyProjectTransactionPermission,
            $viewProjectTransactionPermission,
            $addProjectTransactionPermission,
            $updateProjectTransactionPermission,
            $deleteProjectTransactionPermission,
            $replicateProjectTransactionPermission,
            $restoreProjectTransactionPermission,
            $forceDeleteProjectTransactionPermission,
            $viewResourceProjectTransactionPermission,
            $viewLensPersonalProjectTransactionPermission,
            $runProjectTransactionActionPermission,
            $runProjectTransactionDestructiveActionPermission,
        ];

        $adminRolePermissions = array_filter($superAdminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->startsWith('restore_') &&
                !Str::of($permission->name)->startsWith('forceDelete_') &&
                !Str::of($permission->name)->endsWith('_role') &&
                !Str::of($permission->name)->endsWith('_permission') &&
                !Str::of($permission->name)->contains('2fa');
        });

        // add canBeImpersonatePermission Permission
        array_push($adminRolePermissions, $canBeImpersonatePermission);

        $brokerRolePermissions = array_filter($adminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->startsWith('delete_') &&
                !Str::of($permission->name)->startsWith('replicate_') &&
                !Str::of($permission->name)->startsWith('update_') &&
                !Str::of($permission->name)->endsWith('_user') &&
                !Str::of($permission->name)->contains('impersonate') &&
                !Str::of($permission->name)->contains('Resource');
        });

        // add profile update and delete permissions (as every user should have these permissions)
        array_push(
            $brokerRolePermissions,
            $updateProfilePermission,
            $deleteProfilePermission
        );

        $investorRolePermissions = $brokerRolePermissions;

        $simpleUserRolePermissions = [
            $viewDashboardPermission,
            $viewProfilePermission,
            $updateProfilePermission,
            $deleteProfilePermission,
        ];
        $developerRolePermissions = $simpleUserRolePermissions;

        // Assign permissions to roles
        $superAdminRole->syncPermissions($superAdminRolePermissions);
        $adminRole->syncPermissions($adminRolePermissions);
        $simpleUserRole->syncPermissions($simpleUserRolePermissions);
        $brokerRole->syncPermissions($brokerRolePermissions);
        $developerRole->syncPermissions($developerRolePermissions);
        $investorRole->syncPermissions($investorRolePermissions);
    }
}
