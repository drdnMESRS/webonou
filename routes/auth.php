<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Sso\SsoController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Services\Sso_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

/*
 * login with SSO server
 */

$service = (new Sso_service);

Route::get('/login_sso', function (Request $request) {
    return (new Sso_service)->login_sso($request);
})->name('login');

Route::get('/callback', function (Request $request) {
    return (new Sso_service)->callback($request);
});

Route::get('/user', SsoController::class)->name('user');
