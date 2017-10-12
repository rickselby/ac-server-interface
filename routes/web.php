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

$router->put('config', 'ServerController@config');
$router->put('entrylist', 'ServerController@entryList');
$router->get('log/server', 'ServerController@serverLog');
$router->get('log/system', 'ServerController@systemLog');
$router->get('ping', 'ServerController@ping');
$router->get('results/all', 'ServerController@allResults');
$router->get('results/latest', 'ServerController@results');
$router->get('running', 'ServerController@running');
$router->put('start', 'ServerController@start');
$router->put('stop', 'ServerController@stop');

