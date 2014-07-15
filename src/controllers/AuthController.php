<?php namespace Qlcorp\VextSecurity;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller {

    const ERR_MSG = 'Email and password do not match any accounts';

    public function getIndex() {
        if ( Session::has('error') ) {
            $error = Session::get('error');
            return View::make('vext-security::login')->with('error', $error);
        } else {
            return View::make('vext-security::login');
        }
    }

    public function postAuthenticate() {
        if ( Input::has('email', 'password') ) {
            $email = Input::get('email');
            $password = Input::get('password');

            if ( Auth::attempt(compact('email', 'password')) ){
                return Redirect::intended('/');
            } else {
                return Redirect::to('login')
                    ->with('error', self::ERR_MSG)
                    ->onlyInput('email');
            }
        } else {
            return Redirect::to('login');
        }

    }

}