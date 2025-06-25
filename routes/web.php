<?php


use Illuminate\Support\Facades\Route;


require __DIR__.'/common.php';
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');




