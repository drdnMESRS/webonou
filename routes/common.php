<?php

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Common\LanguageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    // Route to change the activeRole
    Route::get('/change_active_role/{role}', [CommonController::class, 'change_active_role'])->name('change_active_role');
    Route::post('/change_active_year', [CommonController::class, 'change_activeAcademicYear'])->name('change_academic_year');
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanguage'])
        ->name('changeLanguage');
    Route::get('/dashboard', [CommonController::class, 'index'])->name('dashboard');
})->middleware(['auth']);
