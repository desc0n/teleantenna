<?php defined('SYSPATH') or die('No direct script access.');

// Catch-all route for Captcha classes to run
Route::set('captcha', 'captcha(/<group>)')
	->defaults(array(
		'controller' => 'captcha',
		'action' => 'index',
		'group' => NULL));
