<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class GestionDossierHebController extends Controller
{
    /**
     * Display the administrative registration form for Heb.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.dossier-hebergement.index');
    }


    public function create()
    {
        return view('pages.dossier-hebergement.create');
    }
}
