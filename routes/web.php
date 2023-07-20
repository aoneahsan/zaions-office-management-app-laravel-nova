<?php

use App\Http\Controllers\Zaions\TestingController;
use App\Zaions\Enums\BoardStatusEnum;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

use function Spatie\SslCertificate\length;

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


    $reflectionClass = new \ReflectionClass(BoardStatusEnum::class);
    $statuses = [];

    foreach ($reflectionClass->getConstants() as $constantValue) {
        $statuses[] =  Str::ucfirst(Str::replace('_', ' ', $constantValue->value));
    }



    dd($statuses);
});

Route::redirect('/', config('nova.path'));
