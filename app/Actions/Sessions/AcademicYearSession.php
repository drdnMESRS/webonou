<?php

namespace App\Actions\Sessions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AcademicYearSession
{
    public function update_academic_year($yearId)
    {
         Session::put('activeAcademicYear_' . Auth::user()->individu, $yearId);
    }


    public function get_academic_year(){
        return Session::get('activeAcademicYear_' . Auth::user()->individu);
    }
}
