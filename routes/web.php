<?php

use App\Http\Controllers\Zaions\TestingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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
Route::get('/desktop-notification', function () {
    $exitCode = Artisan::call('app:desktop-notification-test-command');
    dd($exitCode);
    // ...
});

Route::get('/z-testing', [TestingController::class, 'zTestingRouteRes']);

Route::redirect('/', config('nova.path'));

