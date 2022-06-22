<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {

        // Update Last Login Time
        $user->lastlogin = date('Y-m-d H:i:s');
        $user->save();


        if($user->is_active == 0)
        {
            auth()->logout();
        }
    }

    public function showLoginForm($lang = '',Request $request)
    {
        if($lang == '')
        {
            $lang = \App\Models\Utility::getValByName('default_language');
        }
        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {

        if($lang == '')
        {
            $lang = \App\Models\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.passwords.email', compact('lang'));
    }


}
