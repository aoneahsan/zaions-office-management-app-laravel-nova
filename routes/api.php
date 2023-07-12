<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\Common\FileUploadController;
use App\Http\Controllers\Zaions\Project\BoardController;
use App\Http\Controllers\Zaions\Project\BoardIdeasController;
use App\Http\Controllers\Zaions\Project\ProjectController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
use App\Http\Controllers\Zaions\Workspace\WorkspaceMemberController;
use App\Http\Controllers\Zaions\WorkSpace\WorkspaceModalConnectionsController;
use App\Http\Controllers\Zaions\ZLink\Common\ApiKeyController;
use App\Http\Controllers\Zaions\ZLink\Common\FolderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['api'])->name('zlink.')->prefix('zlink/v1')->group(function () {
    // Test Routes
    Route::controller(TestController::class)->group(function () {
        Route::get('/notify-user', 'notifyUser');
    });

    // Guest Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

    // API - Authenticated Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Auth State Check API
        // Route::middleware([])->group(function () {
        // Auth Routes
        Route::controller(AuthController::class)->group(function () {
            Route::post('/verify-authentication-status', 'verifyAuthState');
            Route::post('/logout', 'logout');
        });


        // File Upload Controller APIs
        Route::controller(FileUploadController::class)->group(function () {
            Route::post('/file-upload/getSingleFileUrl', 'getSingleFileUrl');
            Route::post('/file-upload/uploadSingleFile', 'uploadSingleFile');
            Route::put('/file-upload/deleteSingleFile', 'deleteSingleFile');
            Route::post('/file-upload/checkIfSingleFileExists', 'checkIfSingleFileExists');
            Route::post('/file-upload/uploadFiles', 'uploadFiles');

            // Route::post('/file-upload/get-image-from-url/url={ImageUrl}', 'uploadSingleFileFromUrl');
        });

        // User Account Related Routes
        Route::controller(UserController::class)->group(function () {
            Route::get('/list-users', 'listUsers');
            Route::get('/user', 'index');
            Route::get('/user/role/permissions', 'getUserPermissions');
            Route::post('/user', 'updateAccountInfo');
            Route::post('/user/username/check', 'checkIfUsernameIsAvailable');
            Route::post('/user/username/update', 'updateUsername');
            // Route::get('/user/{token}', '')->name('password.reset');
            Route::post('/user/delete', 'destroy');
        });

        // Workspace
        Route::controller(WorkSpaceController::class)->group(function () {
            Route::get('/user/workspaces', 'index');
            Route::post('/user/workspaces', 'store');
            Route::get('/user/workspaces/{itemId}', 'show');
            Route::put('/user/workspaces/{itemId}', 'update');
            Route::delete('/user/workspaces/{itemId}', 'destroy');
        });

        // Project
        Route::controller(ProjectController::class)->group(function () {
            Route::get('/user/project', 'index');
            Route::post('/user/project', 'store');
            Route::get('/user/project/{itemId}', 'show');
            Route::put('/user/project/{itemId}', 'update');
            Route::delete('/user/project/{itemId}', 'destroy');
        });

        // Board
        Route::controller(BoardController::class)->group(function () {
            Route::get('/user/project/{projectId}/board', 'index');
            Route::post('/user/project/{projectId}/board', 'store');
            Route::get('/user/project/{projectId}/board/{itemId}', 'show');
            Route::put('/user/project/{projectId}/board/{itemId}', 'update');
            Route::delete('/user/project/{projectId}/board/{itemId}', 'destroy');
        });

        // Board Ideas
        Route::controller(BoardIdeasController::class)->group(function () {
            Route::get('/user/board/{boardId}/boardIdeas', 'index');
            Route::post('/user/board/{boardId}/boardIdeas', 'store');
            Route::get('/user/board/{boardId}/boardIdeas/{itemId}', 'show');
            Route::put('/user/board/{boardId}/boardIdeas/{itemId}', 'update');
            Route::delete('/user/board/{boardId}/boardIdeas/{itemId}', 'destroy');
        });

        // Attach modal (pixel, UTM tag etc.) to workspace.
        Route::controller(WorkspaceModalConnectionsController::class)->group(function () {
            Route::get('/user/wmc/{workspaceId}/modal/{modalType}', 'viewAll');
            Route::post('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'attach');
            Route::delete('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'detach');
        });

        // Workspace member
        Route::controller(WorkspaceMemberController::class)->group(function () {
            Route::post('/user/workspace/{workspaceId}/add-member', 'attachMember');
            Route::delete('/user/workspace/{workspaceId}/remove-member/{memberId}', 'detachMember');
            Route::get('/user/workspace/{workspaceId}/members', 'viewWorkspaceMembers');
            Route::get(
                '/user/workspace/user-collaborated',
                'collaboratedWorkspaces'
            );
            Route::get('/user/workspace/{workspaceId}/user-collaborated-role', 'collaboratedWorkspaceRole');
        });


        // API key
        Route::controller(ApiKeyController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/api-key', 'index');
            Route::post('/user/workspaces/{workspaceId}/api-key', 'store');
            Route::get('/user/workspaces/{workspaceId}/api-key/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/api-key/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/api-key/{itemId}', 'destroy');
        });

        // Folder
        Route::controller(FolderController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/folder', 'index');
            Route::post('/user/workspaces/{workspaceId}/folder', 'store');
            // Route::put('/user/workspaces/{workspaceId}/folders/reorder', 'updateSortOrderNo');
            Route::get('/user/workspaces/{workspaceId}/folder/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/folder/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/folder/{itemId}', 'destroy');
            Route::get('/user/workspaces/{workspaceId}/get/shortLink/folders', 'getShortLinksFolders');
        });
    });
});
