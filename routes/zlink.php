<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\StaticPageController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibBlockController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibPredefinedDataController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LinkInBioController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\ShortLinkController;
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


Route::middleware(['api'])->name('zlink.')->prefix('api/v1')->group(function () {
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


        // User Account Related Routes
        Route::controller(UserController::class)->group(function () {
            Route::get('/list-users', 'listUsers');
            Route::get('/user', 'index');
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

        // ShortLink
        Route::controller(ShortLinkController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/short-links', 'index');
            Route::post('/user/workspaces/{workspaceId}/short-links', 'store');
            Route::get('/user/workspaces/{workspaceId}/short-links/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/short-links/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/short-links/{itemId}', 'destroy');
        });

        // LinkInBio
        Route::controller(LinkInBioController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/link-in-bio', 'index');
            Route::post('/user/workspaces/{workspaceId}/link-in-bio', 'store');
            Route::get('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'destroy');
        });

        // LinkInBio block
        Route::controller(LibBlockController::class)->group(function () {
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'index');
            Route::post('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'store');
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'show');
            Route::put('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'update');
            Route::delete('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'destroy');
        });

        // LinkInBio pre defined data
        Route::controller(LibPredefinedDataController::class)->group(function () {
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-pdd', 'index');
            Route::post('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-pdd', 'store');
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-pdd/{itemId}', 'show');
            Route::put('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-pdd/{itemId}', 'update');
            Route::delete('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-pdd/{itemId}', 'destroy');
        });
    });
});
