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
        // Workspace Model Permissions
        $viewAnyWorkspacePermission = Permission::create(['name' => PermissionsEnum::viewAny_workspace->name]);
        $viewWorkspacePermission = Permission::create(['name' => PermissionsEnum::view_workspace->name]);
        $addWorkspacePermission = Permission::create(['name' => PermissionsEnum::create_workspace->name]);
        $updateWorkspacePermission = Permission::create(['name' => PermissionsEnum::update_workspace->name]);
        $deleteWorkspacePermission = Permission::create(['name' => PermissionsEnum::delete_workspace->name]);
        $replicateWorkspacePermission = Permission::create(['name' => PermissionsEnum::replicate_workspace->name]);
        $restoreWorkspacePermission = Permission::create(['name' => PermissionsEnum::restore_workspace->name]);
        $forceDeleteWorkspacePermission = Permission::create(['name' => PermissionsEnum::forceDelete_workspace->name]);

        // Projects Model Permissions
        $viewAnyProjectsPermission = Permission::create(['name' => PermissionsEnum::viewAny_projects->name]);
        $viewProjectsPermission = Permission::create(['name' => PermissionsEnum::view_projects->name]);
        $addProjectsPermission = Permission::create(['name' => PermissionsEnum::create_projects->name]);
        $updateProjectsPermission = Permission::create(['name' => PermissionsEnum::update_projects->name]);
        $deleteProjectsPermission = Permission::create(['name' => PermissionsEnum::delete_projects->name]);
        $replicateProjectsPermission = Permission::create(['name' => PermissionsEnum::replicate_projects->name]);
        $restoreProjectsPermission = Permission::create(['name' => PermissionsEnum::restore_projects->name]);
        $forceDeleteProjectsPermission = Permission::create(['name' => PermissionsEnum::forceDelete_projects->name]);

        // Board Model Permissions
        $viewAnyBoardPermission = Permission::create(['name' => PermissionsEnum::viewAny_board->name]);
        $viewBoardPermission = Permission::create(['name' => PermissionsEnum::view_board->name]);
        $addBoardPermission = Permission::create(['name' => PermissionsEnum::create_board->name]);
        $updateBoardPermission = Permission::create(['name' => PermissionsEnum::update_board->name]);
        $deleteBoardPermission = Permission::create(['name' => PermissionsEnum::delete_board->name]);
        $replicateBoardPermission = Permission::create(['name' => PermissionsEnum::replicate_board->name]);
        $restoreBoardPermission = Permission::create(['name' => PermissionsEnum::restore_board->name]);
        $forceDeleteBoardPermission = Permission::create(['name' => PermissionsEnum::forceDelete_board->name]);

        // Board Ideas Model Permissions
        $viewAnyBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::viewAny_boardIdeas->name]);
        $viewBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::view_boardIdeas->name]);
        $addBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::create_boardIdeas->name]);
        $updateBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::update_boardIdeas->name]);
        $deleteBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::delete_boardIdeas->name]);
        $replicateBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::replicate_boardIdeas->name]);
        $restoreBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::restore_boardIdeas->name]);
        $forceDeleteBoardIdeasPermission = Permission::create(['name' => PermissionsEnum::forceDelete_boardIdeas->name]);

        // Board Ideas Model Permissions
        $viewAnyBoardStatusPermission = Permission::create(['name' => PermissionsEnum::viewAny_boardStatus->name]);
        $viewBoardStatusPermission = Permission::create(['name' => PermissionsEnum::view_boardStatus->name]);
        $addBoardStatusPermission = Permission::create(['name' => PermissionsEnum::create_boardStatus->name]);
        $updateBoardStatusPermission = Permission::create(['name' => PermissionsEnum::update_boardStatus->name]);
        $deleteBoardStatusPermission = Permission::create(['name' => PermissionsEnum::delete_boardStatus->name]);
        $replicateBoardStatusPermission = Permission::create(['name' => PermissionsEnum::replicate_boardStatus->name]);
        $restoreBoardStatusPermission = Permission::create(['name' => PermissionsEnum::restore_boardStatus->name]);
        $forceDeleteBoardStatusPermission = Permission::create(['name' => PermissionsEnum::forceDelete_boardStatus->name]);

        // Api key Model Permissions
        $viewAnyApiKeyPermission = Permission::create(['name' => PermissionsEnum::viewAny_apiKey->name]);
        $viewApiKeyPermission = Permission::create(['name' => PermissionsEnum::view_apiKey->name]);
        $addApiKeyPermission = Permission::create(['name' => PermissionsEnum::create_apiKey->name]);
        $updateApiKeyPermission = Permission::create(['name' => PermissionsEnum::update_apiKey->name]);
        $deleteApiKeyPermission = Permission::create(['name' => PermissionsEnum::delete_apiKey->name]);
        $replicateApiKeyPermission = Permission::create(['name' => PermissionsEnum::replicate_apiKey->name]);
        $restoreApiKeyPermission = Permission::create(['name' => PermissionsEnum::restore_apiKey->name]);
        $forceDeleteApiKeyPermission = Permission::create(['name' => PermissionsEnum::forceDelete_apiKey->name]);
        // Folder Model Permissions
        $viewAnyFolderPermission = Permission::create(['name' => PermissionsEnum::viewAny_folder->name]);
        $viewFolderPermission = Permission::create(['name' => PermissionsEnum::view_folder->name]);
        $addFolderPermission = Permission::create(['name' => PermissionsEnum::create_folder->name]);
        $updateFolderPermission = Permission::create(['name' => PermissionsEnum::update_folder->name]);
        $deleteFolderPermission = Permission::create(['name' => PermissionsEnum::delete_folder->name]);
        $replicateFolderPermission = Permission::create(['name' => PermissionsEnum::replicate_folder->name]);
        $restoreFolderPermission = Permission::create(['name' => PermissionsEnum::restore_folder->name]);
        $forceDeleteFolderPermission = Permission::create(['name' => PermissionsEnum::forceDelete_folder->name]);
        // Embeded widgets
        $viewAnyEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::viewAny_embededWidget->name]);
        $viewEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::view_embededWidget->name]);
        $addEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::create_embededWidget->name]);
        $updateEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::update_embededWidget->name]);
        $deleteEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::delete_embededWidget->name]);
        $replicateEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::replicate_embededWidget->name]);
        $restoreEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::restore_embededWidget->name]);
        $forceDeleteEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::forceDelete_embededWidget->name]);
        // Workspace pixel connections
        $attachPixelToWorkspacePermission = Permission::create(['name' => PermissionsEnum::attach_pixel_to_workspace->name]);
        $detachPixelFromWorkspacePermission = Permission::create(['name' => PermissionsEnum::detach_pixel_from_workspace->name]);
        $updateWorkspacePixelPermission = Permission::create(['name' => PermissionsEnum::update_workspace_pixel->name]);
        // Workspace utm tags connections
        $attachUtmTagToWorkspacePermission = Permission::create(['name' => PermissionsEnum::attach_utm_tag_to_workspace->name]);
        $detachUtmTagFromWorkspacePermission = Permission::create(['name' => PermissionsEnum::detach_utm_tag_from_workspace->name]);
        $updateWorkspaceUtmTagPermission = Permission::create(['name' => PermissionsEnum::update_workspace_utm_tag->name]);

        // Workspace Members
        $attachWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::attach_workspace_members->name]);
        $detachWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::detach_workspace_members->name]);
        $updateWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::update_workspace_members->name]);

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
            // Workspace
            $viewAnyWorkspacePermission,
            $viewWorkspacePermission,
            $addWorkspacePermission,
            $updateWorkspacePermission,
            $deleteWorkspacePermission,
            $replicateWorkspacePermission,
            $restoreWorkspacePermission,
            $forceDeleteWorkspacePermission,
            // Projects
            $viewAnyProjectsPermission,
            $viewProjectsPermission,
            $addProjectsPermission,
            $updateProjectsPermission,
            $deleteProjectsPermission,
            $replicateProjectsPermission,
            $restoreProjectsPermission,
            $forceDeleteProjectsPermission,
            // Board
            $viewAnyBoardPermission,
            $viewBoardPermission,
            $addBoardPermission,
            $updateBoardPermission,
            $deleteBoardPermission,
            $replicateBoardPermission,
            $restoreBoardPermission,
            $forceDeleteBoardPermission,
            //  Board Ideas
            $viewAnyBoardIdeasPermission,
            $viewBoardIdeasPermission,
            $addBoardIdeasPermission,
            $updateBoardIdeasPermission,
            $deleteBoardIdeasPermission,
            $replicateBoardIdeasPermission,
            $restoreBoardIdeasPermission,
            $forceDeleteBoardIdeasPermission,
            // Board Status
            $viewAnyBoardStatusPermission,
            $viewBoardStatusPermission,
            $addBoardStatusPermission,
            $updateBoardStatusPermission,
            $deleteBoardStatusPermission,
            $replicateBoardStatusPermission,
            $restoreBoardStatusPermission,
            $forceDeleteBoardStatusPermission,
            // Api key
            $viewAnyApiKeyPermission,
            $viewApiKeyPermission,
            $addApiKeyPermission,
            $updateApiKeyPermission,
            $deleteApiKeyPermission,
            $replicateApiKeyPermission,
            $restoreApiKeyPermission,
            $forceDeleteApiKeyPermission,
            // Folder
            $viewAnyFolderPermission,
            $viewFolderPermission,
            $addFolderPermission,
            $updateFolderPermission,
            $deleteFolderPermission,
            $replicateFolderPermission,
            $restoreFolderPermission,
            $forceDeleteFolderPermission,
            // Embeded Widget
            $viewAnyEmbededWidgetPermission,
            $viewEmbededWidgetPermission,
            $addEmbededWidgetPermission,
            $updateEmbededWidgetPermission,
            $deleteEmbededWidgetPermission,
            $replicateEmbededWidgetPermission,
            $restoreEmbededWidgetPermission,
            $forceDeleteEmbededWidgetPermission,
            // Workspace pixel connections
            $attachPixelToWorkspacePermission,
            $detachPixelFromWorkspacePermission,
            $updateWorkspacePixelPermission,
            // Workspace utm tag connections
            $attachUtmTagToWorkspacePermission,
            $detachUtmTagFromWorkspacePermission,
            $updateWorkspaceUtmTagPermission,
            // Workspace member
            $attachWorkspaceMemberPermission,
            $detachWorkspaceMemberPermission,
            $updateWorkspaceMemberPermission,

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
