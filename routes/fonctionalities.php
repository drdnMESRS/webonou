<?php

/*
 * Routes for dous fonctionalities
 */
use Illuminate\Support\Str;

use App\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/* display all residances */

Route::get('/pages/onou/OnouCMDashBoard',
    [CommonController::class, 'dashboard'])
    ->name('diaHeb.dashboard')
    ->middleware(['auth', 'ProgresRole']);

Route::get('/pages/onou/consultHebesgement',
    [App\Http\Controllers\Pages\ResidencesController::class, 'index'])
    ->name('residences.index')
    ->middleware(['auth', 'ProgresRole']);

Route::get('/pages/onou/DossierInscriptionAdministrativeHebC',
    [App\Http\Controllers\Pages\GestionDossierHebController::class, 'index'])
    ->name('diaHeb.show')
    ->middleware(['auth', 'ProgresRole']);

Route::get('/pages/onou/OnouCmLieusGerer',
    [App\Http\Controllers\Pages\GestionLieuController::class, 'index'])
    ->name('onouLieu.show')
    ->middleware(['auth', 'ProgresRole']);

Route::get('/pages/onou/DossierInscriptionAdministrativeDemanderHebDou',
    [App\Http\Controllers\Pages\GestionDossierHebController::class, 'create'])
    ->name('diaHeb.create')
    ->middleware(['auth', 'ProgresRole']);
