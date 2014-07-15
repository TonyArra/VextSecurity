<?php

return array(
	'driver' => 'eloquent',
	'model' => 'User',
	'table' => 'user',
	'reminder' => array(
		'email' => 'emails.auth.reminder',
		'table' => 'password_reminders',
		'expire' => 60,
	),
);