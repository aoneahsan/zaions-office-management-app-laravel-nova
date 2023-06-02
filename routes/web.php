<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\TestingController;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/z-testing', [TestingController::class, 'zTestingRouteRes']);
Route::get('/z-testing', function () {

    dd('okay working');
});

// Route::controller(AuthController::class)->group(function () {
//     Route::get('/login', 'showLoginPage');
// });

Route::redirect('/', config('nova.path'));
