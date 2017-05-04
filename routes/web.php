<?php

use Zhiyi\Plus\Http\Middleware;

// PC路由

Route::prefix('passport')->group(function () {

    // index
    Route::get('index', 'PassportController@index')->name('pc:index');

    // login
    Route::post('login', 'PassportController@login')->name('pc:login');

    // logout
    Route::any('logout', 'PassportController@logout')->name('pc:logout');

    // register
    Route::get('register', 'PassportController@register')->name('pc:register');

    // captcha
    Route::get('captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // 找回密码 
    Route::get('findpwd', 'PassportController@findPassword')->name('pc:findPassword');

    // 完善第三方授权信息
    Route::get('perfect', 'PassportController@perfect')->name('pc:perfect');


});

// 个人中心菜单

Route::prefix('profile')->group(function () {

    // Route::get('all', 'ProfileController@feedAll')->name('pc:feedAll');
    Route::get('index', 'ProfileController@index')->name('pc:myFeed');
    Route::get('related', 'ProfileController@related')->name('pc:related');
    Route::get('fans', 'ProfileController@myFans')->name('pc:myFans');
    Route::get('following', 'ProfileController@following')->name('pc:following');
    Route::get('rank', 'ProfileController@rank')->name('pc:rank');
    Route::get('collection', 'ProfileController@collection')->name('pc:collection');
    Route::get('account', 'ProfileController@account')->name('pc:account');
    Route::get('score', 'ProfileController@score')->name('pc:score');
    Route::get('article', 'ProfileController@article')->name('pc:article');
    
});


//Route::middleware('auth:web')->group(function () {

	// 动态
    Route::get('/home/index', 'HomeController@index')->name('pc:feed');

    // 资讯
    Route::get('/information/index', 'InformationController@index')->name('pc:news');
    
    // 资讯详情
    Route::get('/information/read/{news_id}', 'InformationController@read')->where(['news_id' => '[0-9]+']);

    // 投稿
    Route::get('/information/release', 'InformationController@release')->name('pc:newsrelease');

    Route::get('/information/getNewsList', 'InformationController@getNewsList');
    Route::get('/information/getRecentHot', 'InformationController@getRecentHot');
    Route::get('/information/getAuthorHot', 'InformationController@getAuthorHot');
    Route::post('/information/doSavePost', 'InformationController@doSavePost')->name('pc:doSavePost');
    Route::post('/information/uploadImg', 'InformationController@uploadImg')->name('pc:uploadImg');
    
    // 积分规则
    Route::get('/pc/credit', 'FeedController@credit');
    
    // 个人主页
    Route::get('/pc/profile', 'ProfileController@profile');
    
    // 个人设置
    Route::get('/pc/profile', 'ProfileController@setting');
    
    // 排行榜
    Route::get('/pc/rank', 'ProfileController@rank');
//});
