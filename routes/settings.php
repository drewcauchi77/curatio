<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings', [CompanyController::class, 'create'])->name('settings');
});
