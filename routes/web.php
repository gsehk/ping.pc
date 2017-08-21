<?php

use Zhiyi\Plus\Http\Middleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware as PcMiddleware;

Route::prefix('passport')->group(function () {

    // login
    Route::get('/login', 'PassportController@index')->name('pc:login');

    // token
    Route::get('/mid/{mid}/token/{token}', 'PassportController@token')->name('pc:token');

    // logout
    Route::any('/logout', 'PassportController@logout')->name('pc:logout');

    // register 
    Route::get('/register/{type?}', 'PassportController@register')->where(['type' => '[0-9]+'])->name('pc:register');

    // captcha
    Route::get('/captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // checkcaptcha
    Route::post('/checkcaptcha', 'PassportController@checkCaptcha')->name('pc:checkcaptcha');

    // findpwd 
    Route::get('/findpwd/{type?}', 'PassportController@findPassword')->where(['type' => '[0-9]+'])->name('pc:findpassword');

    // perfect
    Route::get('/perfect', 'PassportController@perfect')->name('pc:perfect');
});

// feeds
Route::prefix('feeds')->group(function () {
    // feeds index
    Route::get('/index', 'FeedController@index')->name('pc:index');
    // feeds list
    Route::get('/', 'FeedController@feeds')->name('pc:feeds');
    // feed detail
    Route::get('/{feed}', 'FeedController@read')->where(['feed' => '[0-9]+'])->name('pc:feedread');
    // feed comments list
    Route::get('/{feed}/comments', 'FeedController@comments')->where(['feed' => '[0-9]+'])->name('pc:feedcomments');
    // feed collections
    Route::get('/collection', 'FeedController@collection')->name('pc:feedcollections');
});


// user profile
Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {

    // user mainpage
    Route::get('/{user_id?}', 'ProfileController@index')->where(['user_id' => '[0-9]+'])->name('pc:mine');

    // user collect page
    Route::get('collect', 'ProfileController@collect')->name('pc:collect');    

    // user article page
    Route::get('article/{user_id?}', 'ProfileController@article')->where(['user_id' => '[0-9]+'])->name('pc:minearc');

    // user setting page
    Route::get('account', 'ProfileController@account')->name('pc:account');

    Route::get('follow', 'ProfileController@follow')->name('pc:follow');

    // user followers
    Route::get('/followers/{user_id?}', 'ProfileController@followers')->where(['user_id' => '[0-9]+'])->name('pc:followers');

    // user followings
    Route::get('/followings/{user_id?}', 'ProfileController@followings')->where(['user_id' => '[0-9]+'])->name('pc:followings');

    // user feeds
    Route::get('feeds', 'ProfileController@feeds');

    // use news
    Route::get('news', 'ProfileController@news');

    /*Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {
        Route::get('related', 'ProfileController@related')->name('pc:related');
        Route::get('following', 'ProfileController@following')->name('pc:following');
        // 个人设置
        Route::post('doSaveAuth', 'ProfileController@doSaveAuth')->name('pc:doSaveAuth'); //保存用户认证信息
        Route::delete('delUserAuth', 'ProfileController@delUserAuth')->name('pc:delUserAuth'); //删除用户认证信息 重新认证
    });*/


});


// news
Route::prefix('news')->group(function () {
    // news index
    Route::get('index', 'NewsController@index')->name('pc:news');
    
    // news detail
    Route::get('/read/{news_id}', 'NewsController@read')->name('pc:newsRead');
    
    // news comments list
    Route::get('/{news_id}/comments', 'NewsController@commnets')->where(['news_id' => '[0-9]+']);
    
    // news release
    Route::get('/release/{news_id?}', 'NewsController@release')->name('pc:newsrelease');

    // get news list
    Route::get('lists', 'NewsController@lists');

    // get recent and hot news 
    Route::get('/getRecentHot', 'NewsController@getRecentHot');

    // get author's hot news
    Route::get('/getAuthorHot', 'NewsController@getAuthorHot');

    // do save and post
    Route::post('/doSavePost', 'NewsController@doSavePost')->name('pc:doSavePost');

    // upload img
    Route::post('/uploadImg', 'NewsController@uploadImg')->name('pc:uploadImg');

    // news collections
    Route::get('/collection', 'FeedController@newsCollect')->name('pc:newscollections');
});

// webmessage
Route::prefix('webmessage')->group(function () {
    Route::get('/webMessage/index/{type?}', 'MessageController@index');
    Route::get('/webMessage/getBody/{type?}', 'MessageController@getMessageBody');
});

// group
Route::prefix('group')->group(function () {
});


