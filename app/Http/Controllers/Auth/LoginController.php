<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/currencies';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param $providerName
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($providerName)
    {
        try {
            return Socialite::driver($providerName)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect($this->redirectTo);
        }
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param $providerName
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($providerName)
    {
        try {
            $socialUser = Socialite::driver($providerName)->user();
            $email = $socialUser->email;
            $user = User::firstOrNew(
                ['email' => $email],
                [
                    'name' => $socialUser->name,
                    'email' => $email,
                    'password' => str_random(10),
                    'remember_token' => str_random(10),
                ]);
            Auth::login($user);
        } catch (\InvalidArgumentException $e) {
        }

        return redirect($this->redirectTo);

    }
}
