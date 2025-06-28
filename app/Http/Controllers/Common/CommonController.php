<?php

namespace App\Http\Controllers\Common;

use App\Actions\Sessions\AcademicYearSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    public function change_activeAcademicYear(Request $request, AcademicYearSession $academicYearSession)
    {

        $academicYearSession->update_academic_year($request->academic_year);

        Session::regenerate();

        return redirect('/dashboard');
    }

    public function change_active_role(Request $request)
    {
        Auth::user()->activeRole = $request->role;
        // Send to pipline to get the menu items.
        Session::regenerate();

        return redirect('/dashboard');
    }

    public function dashboard(Request $request)
    {

        return view('dashboard');
    }
}
