<?php


use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Common\LanguageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    //Route to change the activeRole
    Route::get('/change_active_role/{role}', [CommonController::class, 'change_active_role'])->name('change_active_role');
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanguage'])
        ->name('changeLanguage');
})->middleware(['auth']);
