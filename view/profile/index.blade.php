@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_cont">
    <!--top-->
    <div class="dyn_top">
        @if (!empty($user['cover']))
        <img src="{{ $routes['storage'] }}{{ $user['cover'] }}" class="dynTop_bg" />
        @else
        <img src="{{ $routes['resource'] }}/images/default_cover.png" class="dynTop_bg" />
        @endif
        @if ($user['id'] == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="dyn_huan" style="display:none">更换封面</span>
        @endif
        <div class="dyn_title">{{ $user['name'] }}</div>
        <div class="dynTop_cont">{{ $user['intro'] or '这家伙很懒，什么都没留下'}}</div>
        <div class="dyn_lImg">

            @if (!empty($user['avatar']))
            <img src="{{ $routes['storage'] }}{{ $user['avatar']}} " alt="{{ $user['name'] }}"/>
            @else
            <img src="{{ $routes['resource']}}/images/avatar.png"/>
            @endif
        </div>
    </div>
    <div class="dynTop_b">
        @if (!empty($user['company']))
        <span class="dyn_zy"><i class="icon iconfont icon-gongsi"></i>
            {{ $user['company'] }}
        </span>
        @endif

        @if (!empty($user['year']))
        <span class="dyn_time"><i class="icon iconfont icon-shengri"></i>
            {{ $user['year'] }}
            @if (!empty($user['month']))
            {{ '.'.$user['month'] }}
            @endif
            @if (!empty($user['day']))
            {{ '.'.$user['day'] }}
            @endif

            @if (empty($user['sex']) || $user['sex'] == 3))
            <label>其他</label>
            @elseif($user['sex'] == 2)
            <label>女</label>
            @else
            <label>男</label>
            @endif
        </span>
        @endif

        @if (!empty($user['province']))
        <span class="dyn_address"><i class="icon iconfont icon-site"></i>
            {{ $user['province'] }} 
            @if (!empty($user['city']))
            {{ '·'.$user['city'] }}
            @endif

            @if (!empty($user['area']))
            {{ '·'.$user['area'] }}
            @endif
        </span>
        @endif
        <a href="{{ route('pc:newsrelease') }}" class="dyn_contribute"><i class="icon iconfont icon-feiji tougao"></i>投稿</a>
    </div>
    <div class="dy_cont">
        <!--left-->
        <div class="dy_left"></div>
        <!--《center》-->
        <div class="dy_cCont dy_left_border">
            <div class="dy_center" style="width:664px;">
                <div class="dy_cen">
                    <div style="position:relative;">
                        <div class="artic_left">
                            <a href="{{Route('pc:myFeed', ['type'=>'all'])}}" class="fs-16 @if($type == 'all') dy_cen_333 @endif">全部动态</a>
                            <a href="{{Route('pc:myFeed', ['type'=>'img'])}}" class="fs-16 @if($type == 'img') dy_cen_333 @endif">图片</a>
                            <a href="{{Route('pc:myFeed', ['type'=>'video'])}}" class="fs-16 @if($type == 'video') dy_cen_333 @endif">视频</a>
                            <a href="{{Route('pc:myFeed', ['type'=>'news'])}}" class="fs-16 @if($type == 'news') dy_cen_333 @endif">资讯</a>
                        </div>
                        <a href="{{Route('pc:article')}}"><div class="artic_artic fs-16">文章</div></a>
                    </div>
                    <div style="margin-top: 60px;">
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor ">
                            <span class="cen_beforColor">今<br />天</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive img1">
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="dy_line">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/line.png') }}">
                            </div>
                            <div class="dy_comit">
                                <p>
                                    <span>Ellen：</span> 第一条评论
                                    <a class="fs-14">回复</a>
                                </p>
                                <p>
                                    <span>Nick </span>回复<span>Ellen：</span>
                                    第二条评论
                                </p>
                                <p>
                                    <span>Woody：</span> 回复第一条评论
                                </p>
                                <div class="comit_all fs-12">查看全部评论</div>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor b_bg">
                            <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor b_bg">
                            <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive img1">
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor b_bg">
                            <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_img4">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                            </div>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor b_bg">
                            <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_img6">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_1">
                                <div class="img6_div">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive">
                                </div>
                            </div>
                            <div class="dy_img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                            </div>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img cen_befor b_bg">
                            <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                            <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                            </div>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--<right>-->
        <div class="dy_right" style="margin-left:27px">
            <div class="dyrBottom">
                <ul class="infR_time">
                    <li type="followeds"><a class="hover" href="{{ route('pc:users', ['type'=>1]) }}">粉丝</a></li>
                    <li type="followings"><a href="{{ route('pc:users', ['type'=>2]) }}">关注</a></li>
                    <li type="visitors"><a href="{{ route('pc:users', ['type'=>3]) }}">访客</a></li>
                </ul>

                <div id="followeds" class="userdiv" style="display:block">
                @if (!empty($followeds))
                <ul class="userlist">
                    @foreach ($followeds as $followed)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $followed['id']]) }}">
                        @if (!empty($followed['avatar']))
                        <img src="{{ $routes['storage'] }}{{ $followed['avatar'] }}" />
                        @else
                        <img src="{{ $routes['resource'] }}/images/avatar.png"/>
                        @endif
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $followed['id']]) }}">{{ $followed['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多</a>
                @else
                <span class="nodata">暂无内容</span>
                @endif
                </div>

                <div id="followings" class="userdiv">
                @if (!empty($followings))
                <ul class="userlist">
                    @foreach ($followings as $following)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $following['id']]) }}">
                        @if (!empty($following['avatar']))
                        <img src="{{ $routes['storage'] }}{{ $following['avatar'] }}" />
                        @else
                        <img src="{{ $routes['resource'] }}/images/avatar.png"/>
                        @endif
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $following['id']]) }}">{{ $following['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多</a>
                @else
                <span class="nodata">暂无内容</span>
                @endif  
                </div>


                <div id="visitors" class="userdiv">
                @if (!empty($visitors))
                <ul class="userlist">
                    @foreach ($visitors as $visitor)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $visitor['id']]) }}">
                        @if (!empty($visitor['avatar']))
                        <img src="{{ $routes['storage'] }}{{ $visitor['avatar'] }}" />
                        @else
                        <img src="{{ $routes['resource'] }}/images/avatar.png"/>
                        @endif
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $visitor['id']]) }}">{{ $visitor['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多</a>
                @else
                <span class="nodata">暂无内容</span>
                @endif  
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/md5-min.js') }}"></script>
<script type="text/javascript">
    $(function(){
        // 关注
        $('.infR_time li').hover(function(){
            var type = $(this).attr('type');

            $(this).siblings().find('a').removeClass('hover');
            $(this).find('a').addClass('hover');

            $('.dyrBottom div').hide();
            $('#' + type).show();
        })

        $('.dyn_huan').on('click', function(){
            $('#cover').click();
        })

        $('#cover').on('change', function(e){
            var file = e.target.files[0];
            fileUpload(file, uploadPccover);
        });

        $('.dyn_top').hover(function(){
            $('.dyn_huan').show();
        },function(){
            $('.dyn_huan').hide();
        })
    })

    var uploadPccover = function(image, f, task_id){
        $.ajax({
            url: '/api/v1/users',
            type: 'PATCH',
            data: {cover_storage_task_id: task_id},
            dataType: 'json',
            success: function(res) {
                $('.dynTop_bg').attr('src', image.src);
            }
        });
    }


</script>
@endsection