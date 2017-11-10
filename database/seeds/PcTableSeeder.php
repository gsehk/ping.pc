<?php

use Illuminate\Database\Seeder;
use Zhiyi\Plus\Models\AdvertisingSpace;

class PcTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();
    }

    /**
     * create ads data.
     *
     * @return void
     */
    protected function createData()
    {
        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:top',
            'alias' => 'PC端资讯首页banner',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ]
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ]
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:right',
            'alias' => 'PC端资讯右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ]
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ]
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:feeds:right',
            'alias' => 'PC端动态右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string|必填，广告图',
                    'link' => '链接|string',
                ],
            ],
            'rule' => [
                'image' => [
                    'link' => 'url',
                    'image' => 'required|url',
                ]
            ],
            'message' => [
                'image' => [
                    'image.required' => '广告图不能为空',
                ]
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:list',
            'alias' => 'PC端资讯列表广告',
            'allow_type' => 'pc:newslist',
            'format' => [
                'pc:newslist' => [
                    "name" => "用户名|string|必填，用户名",
                    "content" => "内容|string|广告内容",
                    "image" => "图片|string|广告图片",
                    "time" => "时间|date|广告动态时间",
                    "link" => "链接|string|广告链接"
                ],
            ],
            'rule' => [
                'pc:newslist' => [
                    "name" => "required",
                    "image" => "url",
                    "time" => "required|date",
                    "link" => "required|url",
                    "content" => "required",
                ]
            ],
            'message' => [
                'pc:newslist' => [
                    'name.required' => '广告用户名不能为空',
                    'image.required' => '广告图片链接不能为空',
                    'time.required' => '时间必填',
                    'time.date' => '时间格式错误',
                    'content.required' => '内容必填',
                    'link.required' => '广告连接不能为空',
                    'link.url' => '广告链接无效',
                ]
            ],
        ]);

        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:feeds:list',
            'alias' => 'PC端动态列表广告',
            'allow_type' => 'pc:feedlist',
            'format' => [
                'pc:feedlist' => [
                    "avatar" => "头像图|string|必填，头像",
                    "name" => "用户名|string|必填，用户名",
                    "content" => "内容|string|广告内容",
                    "image" => "图片|string|广告图片",
                    "time" => "时间|date|广告动态时间",
                    "link" => "链接|string|广告链接",
                ],
            ],
            'rule' => [
                'pc:feedlist' => [
                    "name" => "required",
                    "image" => "url",
                    "avatar" => "required|url",
                    "time" => "required|date",
                    "link" => "required|url",
                    "content" => "required",
                ]
            ],
            'message' => [
                'pc:feedlist' => [
                    'name.required' => '广告用户名不能为空',
                    'image.required' => '广告图片链接不能为空',
                    'avatar.required' => '头像图链接不能为空',
                    'avatar.url' => '头像图链接无效',
                    'time.required' => '时间必填',
                    'time.date' => '时间格式错误',
                    'content.required' => '内容必填',
                    'link.required' => '广告连接不能为空',
                    'link.url' => '广告链接无效',
                ]
            ],
        ]);
    }
}