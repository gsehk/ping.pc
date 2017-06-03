<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@show')->name('pc:admin');

// 认证
Route::prefix('auth')->group(function () {
	Route::get('/', 'AuthUserController@getAuthUserList');
	Route::post('/audit/{aid}', 'AuthUserController@audit')->where(['aid'=>'[0-9+]']);
	Route::delete('/del/{$aid}/verified', 'AuthUserController@delAuthInfo')->where(['aid'=>'[0-9]+']);
});

//举报
Route::prefix('denounce')->group(function () {
	Route::get('/', 'DenounceController@getDenounceList');
	Route::post('/handle/{did}', 'DenounceController@handle')->where(['did'=>'[0-9+]']);
});

//积分规则
Route::prefix('credit')->group(function () {
	Route::get('/', 'CreditController@index');
	Route::post('/handle', 'CreditController@handleCreditRule');
});