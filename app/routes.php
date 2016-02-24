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
 
Route::get('/', [
    'as' => 'login',
    'uses' => 'AuthController@getLoginPage'
]);

Route::get('/error', [
    'as' => 'error',
    'uses' => 'AuthController@getError'
]);

Route::group(array('before' => 'csrf'), function()
{
    Route::post('login', [
    'as' => 'post-login-credential',
    'uses' => 'AuthController@postLogin'
    ]);
    
    Route::post('/create-user', [
    'as' => 'create-user',
    'uses' => 'AuthController@postCreateUser'
    ]);
    
});

Route::get('/sign-up-success/{user_id}', [
    'as' => 'sign-up-success',
    'uses' => 'AuthController@getSuccess'
]);
Route::get('/sign-up', [
    'as' => 'sign-up',
    'uses' => 'AuthController@getSignUp'
]);
Route::get('/general-settings', [
    'as' => 'general-settings',
    'uses' => 'UserController@getGeneralSetting'
]);
Route::post('/update-setting', [
    'as' => 'update-setting',
    'uses' => 'UserController@postUpdateUserProfile'
]);
Route::get('/verify/{activationcode}', [
    'as' => 'verify',
    'uses' => 'AuthController@getVerify'
]);
Route::get('/forgot-password', [
    'as' => 'forgot-password',
    'uses' => 'AuthController@getForgotPassword'
]);

Route::post('/forgot-password', [
    'as' => 'forgot-password',
    'uses' => 'AuthController@postForgotPassword'
]);
Route::get('/reset-password/{activationcode}/{user_id}', [
    'as' => 'reset-password',
    'uses' => 'AuthController@getResetPassword'
]);
Route::post('reset-password', [
    'as' => 'reset-password',
    'uses' => 'AuthController@postResetPassword'
]);
Route::group(['after'=>'no-cache'], function(){
Route::get('logout', [
    'as' => 'logout-user',
    'uses' => 'AuthController@getLogout'
]);
});



