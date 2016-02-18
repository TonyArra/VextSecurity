<?php namespace Qlcorp\VextSecurity;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller {

    public function getIndex() {
        $field = Config::get('auth.field', 'email');
        $label = ucfirst($field);

        if ( Session::has('error') ) {
            $error = Session::get('error');
            return View::make('vext-security::login')->with(compact('field', 'label', 'error'));
        } else {
            return View::make('vext-security::login')->with(compact('field', 'label'));
        }
    }

    public function postAuthenticate() {
        $auth_field = Config::get('auth.field', 'email');
        if ( Input::has($auth_field, 'password') ) {
            $field = Input::get($auth_field);
            $password = Input::get('password');

            if ( Auth::attempt(array($auth_field => $field, 'password' => $password)) ){
                return Redirect::intended('/');
            } else {
                return Redirect::to('login')
                    ->with('error', "$auth_field and password do not match any accounts")
                    ->onlyInput($auth_field);
            }
        } else {
            return Redirect::to('login');
        }
    }
}
