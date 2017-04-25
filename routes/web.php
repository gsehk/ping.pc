<?php

use Zhiyi\Plus\Http\Middleware;

// PC路由

Route::get('/pc/login', 'PassportController@index')->name('login');

Route::get('/pc/register', 'PassportController@register')->name('register');

Route::get('pc/set-other', 'PassportController@setOther')->name('register');

// 个人中心菜单

Route::prefix('profile')->group(function () {

    Route::get('all', 'ProfileController@feedAll')->name('pc:feedAll');
    Route::get('feed', 'ProfileController@myFeed')->name('pc:myFeed');
    Route::get('related', 'ProfileController@related')->name('pc:related');
    Route::get('fans', 'ProfileController@myFans')->name('pc:myFans');
    Route::get('following', 'ProfileController@following')->name('pc:following');
    Route::get('rank', 'ProfileController@rank')->name('pc:rank');
    Route::get('collection', 'ProfileController@collection')->name('pc:collection');
    Route::get('account', 'ProfileController@account')->name('pc:account');
    
});


// Route::middleware('auth:web')->group(function () {

	// 动态
    Route::get('/pc/feed', 'FeedController@feed')->name('feed');

    // 资讯
    Route::get('/pc/news', 'FeedController@news')->name('news');
    
    // 积分规则
    Route::get('/pc/credit', 'FeedController@credit');
    
    // 个人主页
    Route::get('/pc/profile', 'ProfileController@profile');
    
    // 个人设置
    Route::get('/pc/profile', 'ProfileController@setting');
    
    // 排行榜
    Route::get('/pc/rank', 'ProfileController@rank');
// });
