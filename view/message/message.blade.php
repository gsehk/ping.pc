@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/message.css')}}"/>

<div class="chat_dialog">
    {{-- 左侧导航 --}}
    <div class="chat_left_wrap">
        <div class="chat_left">
            <ul id="root_list">
                <li @if($type == 0)class="current_room"@endif data-type="0">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-ico_pinglun"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span">评论的</span>
                        <div>{{ $comments->count() > 0 ? $comments[0]->user->name : '没有人' }}评论了我</div>
                        @if($counts['unread_comments_count'] > 0)
                            <span class="chat_num unread_comments_count">{{ $counts['unread_comments_count'] }}</span>
                        @endif
                    </div>
                </li>
                <li @if($type == 1)class="current_room"@endif data-type="1">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-ico_zan"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span">赞过的</span>
                        <div>{{ $likes->count() > 0 ? $likes[0]->user->name : '没有人' }}赞了我</div>
                        @if($counts['unread_likes_count'] > 0)
                            <span class="chat_num">{{ $counts['unread_likes_count'] }}</span>
                        @endif
                    </div>
                </li>
                <li @if($type == 2)class="current_room"@endif data-type="2">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-ico_tongzhi"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span chat_span_noinfo">通知</span>
                    </div>
                </li>
                <li @if($type == 3)class="current_room"@endif data-type="3">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-ico_shenghe"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        @if((isset($pinneds->news) && $pinneds->news->count > 0) || (isset($pinneds->feeds) && $pinneds->feeds->count > 0))
                            <span class="chat_span">审核通知</span>
                            <div>未审核的信息请及时处理</div>
                        @else
                            <span class="chat_span chat_span_noinfo">审核通知</span>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- 消息相关 --}}
    <div class="chat_right @if($type == 5) hide @endif" id="message_wrap">
        <div class="body_title">评论的</div>

        {{-- 审核通知 --}}
        <div class="audit_top hide">
            <div data-value="" class="zy_select t_c gap12 message_select">
                <span>动态评论置顶</span>
                <ul>
                    <li data-value="3" class="active">动态评论置顶</li>
                    <li data-value="4">文章评论置顶</li>
                </ul>
                <i></i>
            </div>
        </div>

        <div class="chat_content_wrap">
            <div class="chat_content">
                <div class="message_cont" id="message_cont">

                </div>
            </div>
        </div>
    </div>

    {{-- 聊天 --}}
    <div class="chat_right @if($type != 5) hide @endif" id="chat_wrap">
        <div class="body_title"></div>

        <div class="chat_content_wrap chat_height">
            <div class="chat_content" id="chat_scroll">
                <div class="chat_cont" id="chat_cont">

                </div>
            </div>
        </div>

        <div class="chat_bottom">
            <textarea placeholder="输入文字" class="chat_textarea" id="chat_text"></textarea>
            <span class="chat_send" onclick="message.sendMessage({{ $cid }})" id="chat_send">发送</span>
        </div>
    </div>
</div>

<script src="{{ asset('zhiyicx/plus-component-pc/js/module.message.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/dexie.js') }}"></script>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + TS.TOKEN,
                'Accept': 'application/json'
            }
        })

        var body_title = $('.body_title');
        var audit_top = $('.audit_top');
        var select = $(".message_select");

        select.on("click", function(e){
            e.stopPropagation();
            return !($(this).hasClass("open")) ? $(this).addClass("open") : $(this).removeClass("open");
        });

        select.on("click", "li", function(e){
            e.stopPropagation();
            var $this = $(this).parent("ul");
            $(this).addClass("active").siblings(".active").removeClass("active");
            $this.prev('span').html($(this).html());
            $this.parent(".zy_select").removeClass("open");
            $this.parent(".zy_select").data("value", $(this).data("value"));


            getData($(this).data("value"));
        });

        $(document).click(function() {
            select.removeClass("open");
        });


        getData(0);

        // 切换消息类型
        $('#root_list').on('click', 'li', function () {
            $(this).hasClass("current_room") || ($(this).addClass("current_room").siblings('.current_room').removeClass('current_room'));
            var type = $(this).data('type');

            if (type == 5) {
                $('#message_wrap').hide();
                $('#chat_wrap').show();
                var cid = $(this).data('cid');
                $('#chat_send').attr('onclick', 'message.sendMessage(' + cid + ')');
                message.listMessage(cid);
            } else {
                $('#message_wrap').show();
                $('#chat_wrap').hide();
                getData(type);
            }
        });

        // 获取消息列表
        function getData(type) {
            $('#message_cont').html('');
            var title = '';
            switch(type) {
                case 0: // 评论
                    title = '评论的';
                    scroll.init({
                        container: '#message_cont',
                        loading: '.message_cont',
                        url: '/message/comments',
                        params: {limit: 20},
                        loadtype: 2
                    });
                    body_title.html(title);
                    body_title.removeClass('hide');
                    audit_top.addClass('hide');

                    break;
                case 1: // 点赞
                    title = '点赞的';
                    scroll.init({
                        container: '#message_cont',
                        loading: '.message_cont',
                        url: '/message/likes',
                        params: {limit: 20},
                        loadtype: 2
                    });
                    body_title.html(title);
                    body_title.removeClass('hide');
                    audit_top.addClass('hide');

                    break;
                case 2: // 通知
                    title = '通知';
                    scroll.init({
                        container: '#message_cont',
                        loading: '.message_cont',
                        url: '/message/notifications',
                        paramtype: 1,
                        params: {limit: 20},
                        loadtype: 2
                    });
                    body_title.html(title);
                    body_title.removeClass('hide');
                    audit_top.addClass('hide');
                    break;
                case 3: // 动态审核
                    scroll.init({
                        container: '#message_cont',
                        loading: '.message_cont',
                        url: '/message/feedCommentTop',
                        params: {limit: 20},
                        loadtype: 2
                    });
                    body_title.addClass('hide');
                    audit_top.removeClass('hide');
                    break;
                case 4: // 资讯审核
                    scroll.init({
                        container: '#message_cont',
                        loading: '.message_cont',
                        url: '/message/newsCommentTop',
                        params: {limit: 20},
                        loadtype: 2
                    });

                    body_title.addClass('hide');
                    audit_top.removeClass('hide');
                    break;
            }
            // 清空未读消息数量
            $('.current_room').find('.chat_num').remove();
        }

        var datas = {};
        datas.list =  window.TS.chat.list;
        // 用户信息
        datas.users = window.TS.chat.users;
        // 会话id
        datas.cid = {{ $cid or 0 }};

        // 初始化message
        message.init(datas);
    });
</script>