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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api\V1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::delete('oauth/token', 'OauthController@logout');
        Route::get('me', 'AccountController@show');
        Route::patch('me', 'AccountController@update');
        Route::apiResource('books', 'BookController');
    });
});