<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router */
$router->group(['prefix' => 'config'], function() use ($router) {
    $router->put('entrylist', 'ConfigController@entryList');
    $router->put('server', 'ConfigController@config');
});

$router->group(['prefix' => 'log'], function() use ($router) {
    $router->get('server', 'ServerController@serverLog');
    $router->get('system', 'ServerController@systemLog');
});

$router->group(['prefix' => 'results'], function() use ($router) {
    $router->get('all', 'ResultsController@allResults');
    $router->get('latest', 'ResultsController@results');
});

$router->get('ping', 'ServerController@ping');
$router->get('running', 'ServerController@running');
$router->put('start', 'ServerController@start');
$router->put('stop', 'ServerController@stop');

