<?php

use Zhiyi\Plus\Http\Middleware;

Route::any('/test2', 'TestController@show');

// PC路由

Route::prefix('passport')->group(function () {

    // login
    Route::get('index', 'PassportController@index')->name('pc:index');

    // dologin
    Route::post('dologin', 'PassportController@doLogin')->name('pc:dologin');

    // logout
    Route::any('logout', 'PassportController@logout')->name('pc:logout');

    // register
    Route::get('register', 'PassportController@register')->name('pc:register');

    // doregister
    Route::post('doregister', 'PassportController@doRegister')
        ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
        ->middleware(Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
        ->middleware(Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
        ->middleware(Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
    ;



    // captcha
    Route::get('captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // checkcaptcha
    Route::post('checkcaptcha', 'PassportController@checkCaptcha')->name('pc:checkcaptcha');

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
    Route::get('collection', 'ProfileController@collection')->name('pc:collection');
    Route::get('account', 'ProfileController@account')->name('pc:account');
    Route::get('article', 'ProfileController@article')->name('pc:article');
    // 个人设置
    Route::post('doSaveAuth', 'ProfileController@doSaveAuth')->name('pc:doSaveAuth'); //保存用户认证信息
    Route::delete('delUserAuth', 'ProfileController@delUserAuth')->name('pc:delUserAuth'); //删除用户认证信息 重新认证
    
});


//Route::middleware('auth:web')->group(function () {

	// 动态
    Route::get('/home/index', 'HomeController@index')->name('pc:feed');
    Route::get('/home/follows', 'HomeController@getFollowFeeds');
    Route::get('/home/hots', 'HomeController@getHotFeeds');
    Route::get('/home/feeds', 'HomeController@getNewFeeds');
    Route::get('/home/checkin', 'HomeController@checkin');
    // Route::post('/home/{feed_id}/comment', 'HomeController@addComment')->where(['feed_id' => '[0-9]+']);

    // 资讯
    Route::get('/information/index', 'InformationController@index')->name('pc:news');
    // 资讯详情
    Route::get('/information/read/{news_id}', 'InformationController@read')->where(['news_id' => '[0-9]+']);
    // 评论列表
    Route::get('/information/{news_id}/comments', 'InformationController@getCommentList')->where(['news_id' => '[0-9]+']);
    // 投稿
    Route::get('/information/release', 'InformationController@release')->name('pc:newsrelease');

    Route::get('/information/getNewsList', 'InformationController@getNewsList');
    Route::get('/information/getRecentHot', 'InformationController@getRecentHot');
    Route::get('/information/getAuthorHot', 'InformationController@getAuthorHot');
    Route::post('/information/doSavePost', 'InformationController@doSavePost')->name('pc:doSavePost');
    Route::post('/information/uploadImg', 'InformationController@uploadImg')->name('pc:uploadImg');
    
    // 积分规则
    Route::get('/credit/index', 'CreditController@index')->name('pc:credit');
    // 排行榜
    Route::get('/rank/index', 'RankController@index')->name('pc:rank');
//});
