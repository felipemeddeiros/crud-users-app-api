<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Route to allow user to login
 */
Route::post('/login', "UserController@login");
/**
 * Routes for user althenticated
 */
Route::group(['middleware' => ['auth:api']], function () {

	/**
	 * Routes for users
	 */
    Route::get('/users', "UserController@index");
    Route::post('/users', "UserController@store");
	Route::get('/users/{user}', "UserController@show");
	Route::put('/users/{user}', "UserController@update");
	Route::patch('/users/{user}', "UserController@update");
	Route::delete('/users/{user}', "UserController@destroy");
});
