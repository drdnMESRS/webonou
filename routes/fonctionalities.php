<?php


/*
 * Routes for dous fonctionalities
 */

/* display all residances */

Route::get('/pages/onou/consultHebesgement', [App\Http\Controllers\Pages\ResidencesController::class, 'index'])
    ->name('residences.index')
    ->middleware(['auth']);
