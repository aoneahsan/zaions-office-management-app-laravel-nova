<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\StaticPageController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
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


Route::middleware(['api'])->name('zaions.')->prefix('zaions/v1')->group(function () {
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
    });
});
