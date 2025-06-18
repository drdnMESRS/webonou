<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CommonController extends Controller
{

    public function change_active_role(Request $request){

        Auth::user()->activeRole  = $request->role;

        Session::regenerate();

        return redirect('/dashboard');

    }
}
