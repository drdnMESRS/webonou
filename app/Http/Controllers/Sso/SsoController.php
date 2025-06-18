<?php

namespace App\Http\Controllers\Sso;

use App\Http\Controllers\Controller;
use App\Models\Ppm\Ref_compte;
use App\Services\Sso_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SsoController extends Controller
{

    public function __invoke(Request $request)
    {
        $response = (new Sso_service())->user_details($request);

        $userName = $response->json()['nom_utilisateur'];

        // user should log in with fortifying
        $user = Ref_compte::where('nom_utilisateur',$userName )->first();

        Auth::login($user);

        Session::put('user_data_'.Auth::user()->individu, $response->json());

        // initiate the default role;

        Auth::user()->activeRole = Auth::user()->affectationAll[0]['id'];

        Session::regenerate();

        return redirect('/dashboard');
    }

}
