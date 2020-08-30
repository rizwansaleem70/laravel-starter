<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::group(['namespace' => 'API'], function () {
    Route::post('login', 'Auth\AuthenticateApiController@login');
    Route::post('register', 'Auth\AuthenticateApiController@register');
    Route::post('forget_password', 'Auth\ForgetPasswordApiController@forgotPassword');
    Route::post('reset_password', 'Auth\ForgetPasswordApiController@resetPassword');
});
