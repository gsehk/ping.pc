<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class BaseController extends Controller
{
    protected $view = array();
    protected $user = array();

	/**
	 * @Author Foreach<missu082500@163.com>
	 */
    public function __construct()
    {
    	// 初始化网站信息
        $this->initSite();

        // 初始化用户
        $this->initUser();
    }

    /**
     * 初始化网站信息
     * @Author Foreach<missu082500@163.com>
     */
    public function initSite()
    {
        $site['nav'] = ['news'=>route('pc:news'), 'feed'=>route('pc:feed')];
        return $site;
    }

    /**
     * 初始化用户信息
     * @Author Foreach<missu082500@163.com>
     */
    public function initUser()
    {

    }
}
