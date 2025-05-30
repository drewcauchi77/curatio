
<?php

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('modules/create', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('modules/generate', [YoutubeController::class, 'index'])->name('modules.generate');
    Route::post('modules/generate', [YoutubeController::class, 'store'])->name('modules.generate.store');
    Route::get('modules/auth', [YoutubeController::class, 'auth'])->name('modules.generate.auth');
    Route::get('modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
});
