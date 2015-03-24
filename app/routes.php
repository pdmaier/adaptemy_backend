<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//define most common used patters to be used
Route::pattern('guid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}+');//regex to parse guids
Route::pattern('id', '[0-9]+');//regex for simple ids
Route::pattern('username', '[a-zA-Z0-9_.]+');
Route::pattern('password', '(?=.*\d).{6,24}+');
//end of most common used patterns

//attach the home routes to \HomeController
Route::get('/','\HomeController@getIndex');
Route::get('/api','\HomeController@getApi');
Route::get('/api/v1.0','\HomeController@getV1');

//attach routes to the controllers
Route::controller('api/v1.0/user', 'V1\UserController');
Route::controller('api/v1.0/studentLearning', 'V1\StudentLearningController');