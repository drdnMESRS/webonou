<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class GestionLieuController extends Controller
{
    /**
     * Display the administrative registration form for Heb.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.gestion-lieu.index');
    }
}
