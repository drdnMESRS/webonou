<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class ResidencesController extends Controller
{
    public function index()
    {
        return view('pages.residences.index');
    }
}
