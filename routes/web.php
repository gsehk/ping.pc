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
    Route::get('/', 'FeedController@index')->name('pc:feeds');

    // feeds list
    Route::get('/list', 'FeedController@list');

    // feed detail
    Route::get('/{feed}', 'FeedController@read')->where(['feed' => '[0-9]+'])->name('pc:feedread');

    // feed comments list
    Route::get('/{feed}/comments', 'FeedController@comments')->where(['feed' => '[0-9]+'])->name('pc:feedcomments');

    // feed collections
    Route::get('/collection', 'FeedController@collection')->name('pc:feedcollections');
});

// rank
Route::prefix('rank')->group(function () {
    // rank list
    Route::get('/{mold?}', 'RankController@index')->where(['mold' => '[0-9]+'])->name('pc:rank');
    Route::get('/rankList', 'RankController@_getRankList')->name('pc:ranklist');
});

Route::prefix('account')->middleware(PcMiddleware\CheckLogin::class)->group(function () {
    Route::get('index', 'AccountController@index')->name('pc:account');

    Route::get('authenticate', 'AccountController@authenticate')->name('pc:authenticate');

    Route::get('tags', 'AccountController@tags')->name('pc:tags');
    
    Route::post('authenticate', 'AccountController@doAuthenticate');
});

// user profile
Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {

    // user mainpage
    Route::get('/{user_id?}', 'ProfileController@index')->where(['user_id' => '[0-9]+'])->name('pc:mine');

    // user collect page
    Route::get('collect', 'ProfileController@collect')->name('pc:collect');

    // user article page
    Route::get('article/{user_id?}', 'ProfileController@article')->where(['user_id' => '[0-9]+'])->name('pc:minearc');
    
    // user followers
    Route::get('/followers/{user_id?}', 'ProfileController@followers')->where(['user_id' => '[0-9]+'])->name('pc:followers');

    // user followings
    Route::get('/followings/{user_id?}', 'ProfileController@followings')->where(['user_id' => '[0-9]+'])->name('pc:followings');

    // user feeds
    Route::get('feeds', 'ProfileController@feeds');

    // use news
    Route::get('news', 'ProfileController@news');
});

// users
Route::prefix('users')->group(function () {
    // find users
    Route::get('/{type?}', 'UserController@index')->where(['type' => '[1-3]'])->name('pc:users');

    // get users
    Route::get('/getusers', 'UserController@getUsers');

    // user followers
    Route::get('/followers/{user_id?}', 'UserController@followers')->where(['user_id' => '[0-9]+'])->name('pc:followers');

    // user followings
    Route::get('/followings/{user_id?}', 'UserController@followings')->where(['user_id' => '[0-9]+'])->name('pc:followings');

    // get follow users
    Route::get('/getfollows', 'UserController@getFollows');

});


// news
Route::prefix('news')->group(function () {
    // news index
    Route::get('/', 'NewsController@index')->name('pc:news');

    // get news list
    Route::get('/list', 'NewsController@list');

    // news detail
    Route::get('/{news_id}', 'NewsController@read')->where(['news_id' => '[0-9]+'])->name('pc:newsread');

    // news comments list
    Route::get('/{news_id}/comments', 'NewsController@commnets')->where(['news_id' => '[0-9]+']);

    // news release
    Route::get('/release/{news_id?}', 'NewsController@release')->name('pc:newsrelease');

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


