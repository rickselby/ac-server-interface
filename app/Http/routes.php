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

$app->get('ping', 'ServerController@ping');
$app->put('config', 'ServerController@config');
$app->put('entrylist', 'ServerController@entryList');
$app->put('start', 'ServerController@start');
$app->put('stop', 'ServerController@stop');
$app->get('running', 'ServerController@running');
$app->get('results/latest', 'ServerController@results');
$app->get('results/all', 'ServerController@allResults');
$app->get('log/server', 'ServerController@serverLog');
$app->get('log/system', 'ServerController@systemLog');
