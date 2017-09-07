<?php

use Zhiyi\Plus\Http\Middleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware as PcMiddleware;

Route::prefix('passport')->group(function () {
    // 登录
    Route::get('/login', 'PassportController@index')->name('pc:login');

    // 登录成功记录token
    Route::get('/token/{token}/{type}', 'PassportController@token')->name('pc:token');

    // 登出
    Route::any('/logout', 'PassportController@logout')->name('pc:logout');

    // 注册
    Route::get('/register/{type?}', 'PassportController@register')->where(['type' => '[0-9]+'])->name('pc:register');

    // 获取验证码
    Route::get('/captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // 检验验证码
    Route::post('/checkcaptcha', 'PassportController@checkCaptcha')->name('pc:checkcaptcha');

    // 找回密码
    Route::get('/findpwd/{type?}', 'PassportController@findPassword')->where(['type' => '[0-9]+'])->name('pc:findpassword');

    // 完善资料
    Route::get('/perfect', 'PassportController@perfect')->name('pc:perfect');
});

Route::prefix('feeds')->group(function () {
    // 动态首页
    Route::get('/', 'FeedController@index')->name('pc:feeds');

    // 获取动态列表
    Route::get('/list', 'FeedController@list');

    // 获取单条微博
    Route::get('/getfeed', 'FeedController@feed');

    // 动态详情
    Route::get('/{feed}', 'FeedController@read')->where(['feed' => '[0-9]+'])->name('pc:feedread');

    Route::get('/{feed}/comments', 'FeedController@comments')->where(['feed' => '[0-9]+']);

    Route::get('/collection', 'FeedController@collection')->name('pc:feedcollections');
});

Route::prefix('question')->group(function () {

    // 问答
    Route::get('/', 'QuestionController@index')->name('pc:question');
});

Route::prefix('rank')->group(function () {
    // 排行榜
    Route::get('/{mold?}', 'RankController@index')->where(['mold' => '[0-9]+'])->name('pc:rank');

    // 获取排行榜列表
    Route::get('/rankList', 'RankController@_getRankList')->name('pc:ranklist');
});

Route::prefix('account')->middleware(PcMiddleware\CheckLogin::class)->group(function () {
    // 基本设置
    Route::get('/index', 'AccountController@index')->name('pc:account');

    // 认证
    Route::get('/authenticate', 'AccountController@authenticate')->name('pc:authenticate');

    // 提交认证
    Route::post('/authenticate', 'AccountController@doAuthenticate');

    // 标签管理
    Route::get('/tags', 'AccountController@tags')->name('pc:tags');

    // 安全设置
    Route::get('/security', 'AccountController@security')->name('pc:security');

    // 我的钱包
    Route::get('/wallet/{type?}', 'AccountController@wallet')->where(['type' => '[1-5]'])->name('pc:wallet');

    // 交易明细
    Route::get('/wallet/records', 'AccountController@records')->name('pc:walletrecords');

    // 交易明细
    Route::get('/wallet/record/{record_id}', 'AccountController@record')->name('pc:walletrecord');
});

Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {

    // 个人主页
    Route::get('/{user?}', 'ProfileController@index')->where(['user' => '[0-9]+'])->name('pc:mine');

    // use news
    Route::get('news', 'ProfileController@news')->name('pc:profilenews');

    // user collect page
    Route::get('collect', 'ProfileController@collect')->name('pc:profilecollect');

    // user article page
    Route::get('article/{user?}', 'ProfileController@article')->where(['user' => '[0-9]+'])->name('pc:minearc');

    // user feeds
    Route::get('feeds', 'ProfileController@feeds');
});

Route::prefix('users')->group(function () {
    // 找人
    Route::get('/{type?}', 'UserController@index')->where(['type' => '[1-3]'])->name('pc:users');

    // 找人获取用户
    Route::get('/getusers', 'UserController@getUsers');

    // 粉丝
    Route::middleware(PcMiddleware\CheckLogin::class)->get('/followers/{user_id?}', 'UserController@followers')->where(['user_id' => '[0-9]+'])->name('pc:followers');

    // 关注
    Route::middleware(PcMiddleware\CheckLogin::class)->get('/followings/{user_id?}', 'UserController@followings')->where(['user_id' => '[0-9]+'])->name('pc:followings');

    // 获取粉丝关注用户
    Route::get('/getfollows', 'UserController@getFollows');
});


Route::prefix('news')->group(function () {
    // 资讯
    Route::get('/', 'NewsController@index')->name('pc:news');

    // 获取资讯列表
    Route::get('/list', 'NewsController@list');

    // 资讯详情
    Route::get('/{news_id}', 'NewsController@read')->where(['news_id' => '[0-9]+'])->name('pc:newsread');

    // 投稿
    Route::middleware(PcMiddleware\CheckLogin::class)->get('/release/{news_id?}', 'NewsController@release')->name('pc:newsrelease');

    // news comments list
    Route::get('/{news_id}/comments', 'NewsController@comments')->where(['news_id' => '[0-9]+']);

    // upload img
    Route::post('/uploadImg', 'NewsController@uploadImg')->name('pc:uploadImg');

    // news collections
    Route::get('/collection', 'FeedController@newsCollect')->name('pc:newscollections');
});

Route::prefix('webmessage')->group(function () {
});

Route::prefix('group')->group(function () {
    // 圈子列表
    Route::get('/', 'GroupController@index')->name('pc:group');

    // 圈子详情
    Route::get('/{group_id?}', 'GroupController@read')->where(['group_id' => '[1-9]+'])->name('pc:groupread');

    // 获取圈子列表
    Route::get('list', 'GroupController@list');

    // 获取圈子动态列表
    Route::get('postLists', 'GroupController@postLists');

    // 获取单条圈子动态信息
    Route::get('getPost', 'GroupController@getPost');

    // 圈子动态详情
    Route::get('/{group_id}/post/{post_id}', 'GroupController@postDetail')->where(['group_id' => '[0-9]+', 'post_id' => '[0-9]+'])->name('pc:grouppost');

    // 圈子动态获取评论列表
    Route::get('/{group_id}/post/{post_id}/comments', 'GroupController@comments')->where(['group_id' => '[0-9]+', 'post_id' => '[0-9]+']);

});

Route::prefix('search')->group(function () {
    Route::get('/{type?}/{keywords?}', 'SearchController@index')->where(['type' => '[1-6]+'])->name('pc:search');
    Route::get('/data', 'SearchController@getData');
});

// 三方用户信息授权
Route::prefix('socialite')->group(function () {

    // 三方获取信息跳转
    Route::get('/{service}', 'SocialiteController@redirectToProvider')->where(['service' => '[a-z]+'])->name('pc:socialite');

    // 三方回调
    Route::get('/{service}/callback', 'SocialiteController@handleProviderCallback')->where(['service' => '[a-z]+']);

    Route::get('/{type?}', 'SocialiteController@bind')->where(['type' => '[0-9]+'])->name('pc:socialitebind');
});


