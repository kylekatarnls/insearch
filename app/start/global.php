<?php

ini_set('xdebug.max_nesting_level', 2000);

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/models/tools',
	app_path().'/database/seeds',
	app_path().'/utils',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
use Whoops\Handler\Handler;

// Use the Laravel IoC to get the Whoops\Run instance, if whoops
// is available (which will be the case, by default, in the dev
// environment)

if(method_exists($app['whoops.handler'], 'setResourcesPath'))
{
	$app['whoops.handler']->setResourcesPath(app_path() . '/utils/exception/resources');
}

if(App::bound("whoops"))
{
	ob_start(function ($content)
	{
		return str_replace("\\n", "\n", preg_replace('#^<!DOCTYPE\shtml>.+<!DOCTYPE\shtml>#', '<!DOCTYPE html>', str_replace("\n", "\\n", $content)));
	});
}

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

sbp_include(app_path().'/filters.php');

