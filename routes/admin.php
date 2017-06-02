<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@show')->name('pc:admin');

// 认证
Route::prefix('auth')->group(function () {
	Route::get('/', 'AuthUserController@getAuthUserList');
	Route::post('/audit/{aid}', 'AuthUserController@audit')->where(['aid'=>'[0-9+]']);
	Route::delete('/del/{$aid}/verified', 'AuthUserController@delAuthInfo')->where(['aid'=>'[0-9]+']);
});