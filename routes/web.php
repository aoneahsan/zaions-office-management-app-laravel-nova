<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Zaions\TestingController;
use Illuminate\Support\Facades\Route;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::redirect('/dashboard', '/dashboards/main')->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/z-testing', [TestingController::class, 'zTestingRouteRes']);


// Route::redirect('/', config('nova.path'));

require __DIR__ . '/auth.php';
