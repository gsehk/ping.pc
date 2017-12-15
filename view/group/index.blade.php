@section('title')
圈子
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-idxgroup">
    <div class="left_container g-mn">
        <div class="group_container">
            <div class="group_navbar">
                <a class="active" href="javascript:;" data-cate="1">全部圈子</a>
                <a class="active" href="javascript:;" data-cate="3">附近圈子</a>
                @if(!empty($TS))
                <a href="javascript:;" data-cate="2">我加入的</a>
                @endif
            </div>
            <div class="m-chip">
                @foreach ($cates as $cate)
                    <span class="u-chip" rel="{{$cate->id}}">{{$cate->name}}</span>
                @endforeach
                @if ($cates->count() > 10)
                    <a class="u-chip cur" id="J-show">查看更多</a>
                @endif
            </div>
            <div class="m-search-area f-dn">
                <input class="search-ipt" id="location" type="text" name="search_key" placeholder="输入关键字搜索">
                <div class="area-searching font14 hide"></div>
                <a class="search-icon" id="J-search-area">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-search"></use></svg>
                </a>
            </div>
            <div class="group_list clearfix" id="group_box">
            </div>
        </div>
    </div>

    <div class="right_container g-side">
        <div class="g-sidec f-mb30">
            <a href="{{route('pc:groupcreate')}}">
                <div class="u-btn f-mb20">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-label"></use></svg>
                    <span>创建圈子</span>
                </div>
            </a>
            <div class="u-btn">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-writing"></use></svg>
                <span>发帖</span>
            </div>
        </div>
        <div class="g-sidec f-mb30">
            <p>共有 <span class="s-fc3 f-fs5">8000</span> 个兴趣圈子，等待你的加入！</p>
        </div>
        <div class="g-sidead f-mb30">
            <img src="{{ asset('zhiyicx/plus-component-pc/css/img/ad.png') }}">
        </div>
        <!-- 热门圈子 -->
        @include('pcview::widgets.hotgroups')
    </div>
</div>
@endsection

@section('scripts')
<script>
    $("#location").keyup(function(event){
        last = event.timeStamp;
        setTimeout(function(){
            if(last - event.timeStamp == 0){
                location_search();
            }
        }, 500);
    })

    $('.area-searching').on('click', 'a', function() {
        $('#location').val($(this).text());
        $('.area-searching').hide();
    })

    function location_search(event)
    {
        var val = $.trim($("#location").val());
        var area_searching = $(".area-searching");
        area_searching.html('').hide();
        if (val != "") {
            $.ajax({
                type: "GET",
                url: '/api/v2/locations/search',
                data: {
                    name: val
                },
                success: function(res) {
                    if (res.length > 0) {
                        $.each(res, function(key, value) {
                            if (key < 3) {
                                var text = tree(value.tree);
                                var html = '<a>' + text + '</a>';
                                area_searching.append(html);
                            }
                        });
                        area_searching.show();
                    }
                }
            });
        }
    }
    function tree(obj)
    {
        var text = '';
        if (obj.parent != null) {
            text = tree(obj.parent) + ' ' +  obj.name;
        } else {
            text = obj.name;
        }
        return text;
    }

$('#group_box').on('click', '.J-join', function(){
    checkLogin();
    var _this = this;
    var status = $(this).attr('status');
    var group_id = $(this).attr('gid');
    var joinCount = parseInt($('#join-count-'+group_id).text());
    group(status, group_id, function(){
        if (status == 1) {
            $(_this).text('+加入');
            $(_this).attr('status', 0);
            $(_this).removeClass('joined');
            $('#join-count-'+group_id).text(joinCount - 1);
        } else {
            $(_this).text('已加入');
            $(_this).attr('status', 1);
            $(_this).addClass('joined');
            $('#join-count-'+group_id).text(joinCount + 1);
        }
    });
});

setTimeout(function() {
    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        paramtype: 1,
        url: '/group/list',
        params: {limit: 15}
    });
}, 300);

// 圈子分类筛选
$('.m-chip span').on('click', function() {
    var cateid = $(this).attr('rel');
    $('#group_box').html('');

    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        url: '/group/list',
        paramtype: 1,
        params: {category_id: cateid, limit: 15}
    });

    $('.m-chip span').removeClass('cur');
    $(this).addClass('cur');
});

// 切换分类
$('.group_navbar a').on('click', function() {
    var cate = $(this).data('cate');
    $('#group_box').html('');
    if (cate == 1) {
        $('.m-chip').show();
    } else {
        $('.m-chip').hide();
    }

    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        url: '/group/list',
        paramtype: 1,
        params: {cate: cate, limit: 15}
    });

    $('.group_navbar a').removeClass('active');
    $(this).addClass('active');
});

</script>
@endsection