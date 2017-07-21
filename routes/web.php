<?php

use Zhiyi\Plus\Http\Middleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware as PcMiddleware;

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
        ->middleware(Middleware\V1\VerifyPhoneNumber::class) // 验证手机号码是否正确
        ->middleware(Middleware\V1\VerifyUserNameRole::class) // 验证用户名规则是否正确
        ->middleware(Middleware\V1\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
        ->middleware(Middleware\V1\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
        ->middleware(Middleware\V1\VerifyPhoneCode::class) // 验证验证码释放正确
    ;



    // captcha
    Route::get('captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // checkcaptcha
    Route::post('checkcaptcha', 'PassportController@checkCaptcha')->name('pc:checkcaptcha');

    // findpwd 
    Route::get('findpwd', 'PassportController@findPassword')->name('pc:findPassword');

    // dofindpwd
    Route::patch('dofindpwd', 'PassportController@doFindpwd')
        ->middleware(Middleware\V1\VerifyPhoneNumber::class) // 验证手机号格式
        ->middleware(Middleware\V1\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
        ->middleware(Middleware\V1\VerifyPhoneCode::class) // 验证手机号码验证码是否正确
    ;

    // perfect
    Route::get('perfect', 'PassportController@perfect')->name('pc:perfect');
});



// UCenter
Route::get('index/{user_id?}', 'ProfileController@index')->where(['user_id' => '[0-9]+'])->name('pc:myFeed');
Route::get('article/{user_id?}/{type?}', 'ProfileController@article')->where(['user_id' => '[0-9]+'])->name('pc:article');
Route::prefix('profile')->get('/users/{user_id}', 'ProfileController@getUserFeeds')->where(['user_id' => '[0-9]+']); //个人中心
Route::prefix('profile')->get('/news/{user_id}', 'ProfileController@getNewsList')->where(['user_id' => '[0-9]+']); //个人中心资讯列表
Route::prefix('profile')->get('/collection/{user_id}', 'ProfileController@getCollectionList')->where(['user_id' => '[0-9]+']); //个人中心资讯列表
Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {
    Route::get('related', 'ProfileController@related')->name('pc:related');
    Route::get('nexus/{type?}/{user_id?}', 'ProfileController@users')->name('pc:users');
    Route::get('following', 'ProfileController@following')->name('pc:following');
    Route::get('collection', 'ProfileController@collection')->name('pc:collection');
    Route::get('account', 'ProfileController@account')->name('pc:account');
    // 个人设置
    Route::get('cropper', 'ProfileController@cropper'); 
    Route::post('doSaveAuth', 'ProfileController@doSaveAuth')->name('pc:doSaveAuth'); //保存用户认证信息
    Route::delete('delUserAuth', 'ProfileController@delUserAuth')->name('pc:delUserAuth'); //删除用户认证信息 重新认证
});

Route::prefix('pc')->group(function () {
    /* 动态列表 */
    Route::get('feeds', 'HomeController@feeds');
    /* 动态详情 */
    Route::get('feeds/{feed}', 'HomeController@read')->name('PC:FeedRead')->where(['feed' => '[0-9]+']);
    /* 动态评论列表 */
    Route::get('feeds/{feed}/comments', 'HomeController@comments');
    /* 动态收藏 */
    Route::get('feeds/collection', 'HomeController@collection');
    /* 文章收藏 */
    Route::get('news/collection', 'HomeController@newsCollect');
});

	// 动态
    Route::get('/home/index', 'HomeController@index')->name('pc:feed');
    Route::get('/home/checkin', 'HomeController@checkin');
    // 举报
    Route::post('/feed/{feed_id}/denounce', 'FeedController@denounce')->where(['feed_id' => '[0-9]+']);

    // 资讯
    Route::get('/information/index', 'InformationController@index')->name('pc:news');
    // 资讯详情
    Route::get('/information/read/{news_id}', 'InformationController@read')->where(['news_id' => '[0-9]+']);
    // 评论列表
    Route::get('/information/{news_id}/comments', 'InformationController@getCommentList')->where(['news_id' => '[0-9]+']);
    // 投稿
    Route::get('/information/release/{news_id?}', 'InformationController@release')->name('pc:newsrelease');

    Route::get('/information/getNewsList', 'InformationController@getNewsList');
    Route::get('/information/getRecentHot', 'InformationController@getRecentHot');
    Route::get('/information/getAuthorHot', 'InformationController@getAuthorHot');
    Route::post('/information/doSavePost', 'InformationController@doSavePost')->name('pc:doSavePost');
    Route::post('/information/uploadImg', 'InformationController@uploadImg')->name('pc:uploadImg');
    
    // 积分规则
    Route::get('/credit/index/{type?}', 'CreditController@index')->name('pc:credit');
    // 排行榜
    Route::get('/rank/index', 'RankController@index')->name('pc:rank');

    Route::get('/webMessage/index/{type?}', 'MessageController@index');
    Route::get('/webMessage/getBody/{type?}', 'MessageController@getMessageBody');
