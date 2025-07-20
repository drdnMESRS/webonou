<?php

namespace App\Http\Controllers\Common;

use App\Actions\Sessions\AcademicYearSession;
use App\Http\Controllers\Controller;
use App\Strategies\Dashboard\DashboadInterfaceContext;
use App\Strategies\Dashboard\Interface\DashboadInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    private DashboadInterface $strategy;

    public function __construct()
    {
        $this->strategy = new DashboadInterfaceContext;

    }

    public function change_activeAcademicYear(Request $request, AcademicYearSession $academicYearSession)
    {

        $academicYearSession->update_academic_year($request->academic_year);
 $redirectTo = $request->input('redirect_to');
        Session::regenerate();

        return redirect( $redirectTo);
    }

    public function change_active_role(Request $request)
    {
        Auth::user()->activeRole = $request->role;
        // Send to pipline to get the menu items.
        Session::regenerate();

        return redirect('/dashboard');
    }

    public function index()
    {
        return view('index');
    }

    public function dashboard(Request $request)
    {
        return $this->strategy->displayDashboard($this->strategy->getstat());
    }
}
