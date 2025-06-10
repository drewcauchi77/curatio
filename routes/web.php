<?php

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\YoutubeChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [SessionController::class, 'create'])->name('login');
    Route::post('login', [SessionController::class, 'authenticate']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'create'])->name('dashboard');

    Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('modules/create', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('modules/generate', [YoutubeChannelController::class, 'index'])->name('modules.index');
    // TODO
    Route::post('modules/connect', [YoutubeChannelController::class, 'connect'])->name('youtube.connect');
    Route::post('modules/disconnect', [YoutubeChannelController::class, 'destroy'])->name('youtube.destroy');

    Route::get('modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');

    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('courses/create', [CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('settings', [CompanyController::class, 'create'])->name('settings');

    Route::post('logout', [SessionController::class, 'destroy']);
});
