<?php

Route::filter('vextAuth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::filter('vextGuest', function()
{
	if (Auth::check()) return Redirect::to('/');
});