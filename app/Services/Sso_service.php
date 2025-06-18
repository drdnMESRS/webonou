<?php

namespace App\Services;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;


class Sso_service
{

    public function login_sso(Request $request){
        $request->session()->put('state', $state = Str::random(40));

        $query= http_build_query([
            'client_id' => env('CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('REDIRECT_URI'),
            'state' => $state,
        ]);

        return redirect(env('SSO_SERVER').'/oauth/authorize?'. $query);
    }



    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     * @throws ConnectionException
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(strlen($state) > 0 && $state === $request->input('state'), InvalidArgumentException::class);

        $response = Http::asForm()->post(env('SSO_SERVER').'/oauth/token', [
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'code' => $request->input('code'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => env('REDIRECT_URI'),
        ]);

        $request->session()->put('access_token', $response->json()['access_token']);
        return redirect('/user');
    }



    //fetch user information and redirect to the home page
    /**
     * @param Request $request
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    function user_details(Request $request): Response|PromiseInterface
    {
        $access_token  = $request->session()->get('access_token');
        return Http::withHeaders(
            [
                "Accept" => "application/json",
                "Authorization" => "Bearer $access_token",
            ]
        )->get(env('SSO_SERVER').'/api/user');
    }
}
