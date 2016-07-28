<?php namespace Qlcorp\VextSecurity;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{

    public function getIndex()
    {
        $field = Config::get('auth.field', 'email');
        $label = ucfirst($field);

        if (Session::has('error')) {
            $error = Session::get('error');
            return View::make('vext-security::login')->with(compact('field', 'label', 'error'));
        } else {
            return View::make('vext-security::login')->with(compact('field', 'label'));
        }
    }

    public function postAuthenticate()
    {
        $auth_field = Config::get('auth.field', 'email');
        if (Input::has($auth_field, 'password')) {
            $field = Input::get($auth_field);
            $password = Input::get('password');
            $lock_timeout = Config::get('auth.user_lock_timeout');

            $user = \User::where('email', $field)
                ->first();

            if (Auth::attempt(array($auth_field => $field, 'password' => $password))) {
                $user_data = json_decode($user->login_history);

                $login_history_json = $user->login_history;
                if (isJson($login_history_json))
                    $login_history = json_decode($login_history_json, true);
                else
                    $login_history = Config::get('auth.user_history');

                $datetime1 = strtotime(end($login_history['failed_attempt']));
                $datetime2 = strtotime(date('Y-m-d h:i:s'));
                $interval = abs($datetime2 - $datetime1);
                $minutes = round($interval / 60);

                if ($password == 'secret') {
                    $login_history['new_user'] = true;
                } else {
                    $login_history['new_user'] = false;
                }
                if ($minutes > $lock_timeout) {
                    $login_history['is_locked'] = 0;
                }
                $user->login_history = json_encode($login_history);
                $user->save();

                if ($login_history['is_locked'] == 1) {

                    echo "Your account has been locked. Try after $lock_timeout minutes";
                    exit;

                    return Redirect::to('login')
                        ->with('error', "Your account has been locked. Try after $lock_timeout minutes.")
                        ->onlyInput($auth_field);
                } else {
                    return Redirect::intended('/');
                }

            } else {
                $user_attempts = Config::get('auth.max_user_attempt');

                if (count($user) > 0) {
                    $login_history_json = $user->login_history;

                    if (isJson($login_history_json))
                        $login_history = json_decode($login_history_json, true);
                    else
                        $login_history = Config::get('auth.user_history');

                    $datetime1 = strtotime(end($login_history['failed_attempt']));
                    $datetime2 = strtotime(date('Y-m-d h:i:s'));
                    $interval = abs($datetime2 - $datetime1);
                    $minutes = round($interval / 60);

                    if ($minutes > $lock_timeout) {
                        $login_history['failed_attempt'] = array();

                        $login_history['is_locked'] = 0;
                        $user->login_history = json_encode($login_history);
                        $user->save();
                    }

                    $login_history['failed_attempt'][] = date('Y-m-d h:i:s');

                    $user->login_history = json_encode($login_history);
                    $user->save();

                    if (count($login_history['failed_attempt']) > $user_attempts) {
                        $login_history['is_locked'] = 1;
                        $user->login_history = json_encode($login_history);
                        $user->save();

                        return Redirect::to('login')
                            ->with('error', "Your account has been locked. Try after $lock_timeout minutes.")
                            ->onlyInput($auth_field);
                    }

                }


                return Redirect::to('login')
                    ->with('error', "$auth_field and password do not match any accounts")
                    ->onlyInput($auth_field);
            }
        } else {
            return Redirect::to('login');
        }
    }
}
