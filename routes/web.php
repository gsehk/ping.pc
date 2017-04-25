<?php

use Zhiyi\Plus\Http\Middleware;

// PC路由

Route::get('/pc/login', 'PassportController@index')->name('login');

Route::middleware('auth:web')->group(function () {

	// 动态
    Route::get('/pc/feed', 'FeedController@feed');

    // 资讯
    Route::get('/pc/news', 'FeedController@news');
    
    // 积分规则
    Route::get('/pc/credit', 'FeedController@credit');
    
    // 个人主页
    Route::get('/pc/profile', 'ProfileController@profile');
    
    // 个人设置
    Route::get('/pc/profile', 'ProfileController@setting');
    
    // 排行榜
    Route::get('/pc/rank', 'ProfileController@rank');
});
