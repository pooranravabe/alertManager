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

//Author:@krrish
//CreatedAt:24/02/2016
Route::get('alertManage/addAlertSubs', [
    'as' => 'alertManage/addAlertSubs',
    'uses' => 'AlertsubscriptionController@addAlertSubs'
]);
Route::post('alertManage/postAlertSubs', [
    'as' => 'alertManage/postAlertSubs',
    'uses' => 'AlertsubscriptionController@postAlertSubs'
]);
Route::get('alertManage/viewAlertSubs', [
    'as' => 'alertManage/viewAlertSubs',
    'uses' => 'AlertsubscriptionController@viewAlertSubs'
]);
Route::post('alertManage/deleteAlertSubs', [
    'as' => 'alertManage/deleteAlertSubs',
    'uses' => 'AlertsubscriptionController@deleteAlertSubs'
]);
Route::post('alertManage/editAlertSubs', [
    'as' => 'alertManage/editAlertSubs',
    'uses' => 'AlertsubscriptionController@editAlertSubs'
]);
Route::post('alertManage/updateAlertSubs', [
    'as' => 'alertManage/updateAlertSubs',
    'uses' => 'AlertsubscriptionController@updateAlertSubs'
]);
Route::get('alertManage/getChannelResDetail', [
    'as' => 'alertManage/getChannelResDetail',
    'uses' => 'ResponseController@getChannelResDetail'
]);

// Alert types added by kapil
Route::get('alert/allTypes', [
    'as' => 'alert/allTypes',
    'uses' => 'AlertController@manageAlertType'
]);

Route::get('alert/addType', [
    'as' => 'alert/addType',
    'uses' => 'AlertController@addMainAlert'
]);


Route::post('alert/addedType', [
    'as' => 'alert/addedType',
    'uses' => 'AlertController@addedMainAlert'
]);

Route::post('alert/editType', [
    'as' => 'alert/editType',
    'uses' => 'AlertController@editMainAlert'
]);

Route::post('alert/updateType', [
    'as' => 'alert/updateType',
    'uses' => 'AlertController@UpdateMainAlert'
]);

Route::post('alert/deleteType', [
    'as' => 'alert/deleteType',
    'uses' => 'AlertController@deleteMainAlert'
]);


Route::post('alert/addSubType', [
    'as' => 'alert/addSubType',
    'uses' => 'AlertController@addSubAlert'
]);

Route::post('alert/addedSubType', [
    'as' => 'alert/addedSubType',
    'uses' => 'AlertController@addedSubAlert'
]);

Route::post('alert/editSubType', [
    'as' => 'alert/editSubType',
    'uses' => 'AlertController@editMainSubAlert'
]);

Route::post('alert/updateSubType', [
    'as' => 'alert/updateSubType',
    'uses' => 'AlertController@UpdateSubAlert'
]);

Route::post('alert/deleteSubType', [
    'as' => 'alert/deleteSubType',
    'uses' => 'AlertController@deleteSubAlert'
]);
// End alert types added by kapil
