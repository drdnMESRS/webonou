<?php

/*
 * Routes for dous fonctionalities
 */

use App\Http\Controllers\Common\CommonController;
use Illuminate\Support\Facades\Route;

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
