<?php

use Illuminate\Http\Request;

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

Route::prefix('auth')->namespace('Api')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('social/{provider}', 'AuthController@redirectToProvider');
    Route::get('social/callback/{provider}', 'AuthController@handleProviderCallback');

    Route::middleware('auth:api')->group(function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::apiResource('users', 'Api\UserController')->middleware(['auth:api']);

Route::get('social-user/{token}', 'Api\UserController@socialUser');
Route::post('password/reset', 'Api\UserController@resetPassword');
Route::get('/home/links', 'HomeController@links');
