<?php namespace Qlcorp\VextSecurity;

class AuthController extends \Controller
{

    public function getIndex()
    {
        $field = \Config::get('auth.field', 'email');
        $label = ucfirst($field);

        if (\Session::has('error')) {
            $error = \Session::get('error');

            return \View::make('vext-security::login')->with(compact('field', 'label', 'error'));
        } else {
            return \View::make('vext-security::login')->with(compact('field', 'label'));
        }
    }

    public function postAuthenticate()
    {
        $auth_field = \Config::get('auth.field', 'email');

        if (\Input::has(array($auth_field, 'password'))) {
            $field = \Input::get($auth_field);
            $password = \Input::get('password');
            $lock_timeout = \Config::get('auth.user_lock_timeout');
            $credentials = array($auth_field => $field, 'password' => $password);

            $user = \User::where('email', $field)
                ->first();

            if (\Auth::attempt($credentials)) {
                $login_history_json = $user->login_history;

                if ($login_history_json) {
                    $login_history = json_decode($login_history_json, true);
                } else {
                    $login_history = \Config::get('auth.user_history');
                }

                $time_period = \Config::get('auth.password_expire_period');
                $alert_days = \Config::get('auth.password_expire_alert_days');

                $last_updated = $login_history['last_password_change'] == '' ? date('Y-m-d h:i:s') : $login_history['last_password_change'];
                $current_date = date('Y-m-d h:i:s');

                $val1 = new \DateTime($last_updated);
                $val2 = new \DateTime($current_date);

                $diff_days = $val2->diff($val1)->format("%a");
                $valid_day = $time_period - $alert_days;

                if ($diff_days > $valid_day) {
                    $login_history['isexpiring'] = 1;
                } else {
                    $login_history['isexpiring'] = 0;
                }

                $valid_day = $time_period;

                if (($diff_days > $valid_day) || $login_history['new_user']) {
                    $login_history['isexpired'] = 1;
                } else {
                    $login_history['isexpired'] = 0;
                }

                if ($login_history['isexpired'] == 1) {
                    \Auth::logout();

                    return \Redirect::to('login')
                        ->with('error', "Your password has expired. Please contact a system administrator.");
                }

                $datetime1 = strtotime(end($login_history['failed_attempt']));
                $datetime2 = strtotime(date('Y-m-d h:i:s'));
                $interval = abs($datetime2 - $datetime1);
                $minutes = round($interval / 60);

                if ($password == 'secret') {
                    $login_history['new_user'] = true;
                } else {
                    if (empty($login_history['last_login'])) {
                        $login_history['last_password_change'] = date('Y-m-d h:i:s', strtotime('yesterday'));
                        $login_history['isexpiring'] = 1;
                    }
                    $login_history['new_user'] = false;
                }

                $login_history['last_login'] = date('Y-m-d h:i:s');

                if ($minutes > $lock_timeout) {
                    $login_history['is_locked'] = 0;
                }

                $user->login_history = json_encode($login_history);
                $user->save();

                if ($login_history['is_locked'] == 1) {
                    \Auth::logout();

                    return \Redirect::to('login')
                        ->with('error', "Your account has been locked. Try after $lock_timeout minutes.")
                        ->onlyInput($auth_field);
                } else {
                    return \Redirect::intended('/');
                }
            } else {
                $user_attempts = \Config::get('auth.max_user_attempt');

                if ($user) {
                    $login_history_json = $user->login_history;

                    if ($login_history_json) {
                        $login_history = json_decode($login_history_json, true);
                    } else {
                        $login_history = \Config::get('auth.user_history');
                    }

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

                        return \Redirect::to('login')
                            ->with('error', "Your account has been locked. Try after $lock_timeout minutes.")
                            ->onlyInput($auth_field);
                    }

                }

                return \Redirect::to('login')
                    ->with('error', "$auth_field and password do not match any accounts")
                    ->onlyInput($auth_field);
            }
        } else {
            return \Redirect::to('login');
        }
    }
}
