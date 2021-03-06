@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<link rel="stylesheet" href="{{ URL::asset('assets/pc/css/message.css')}}"/>

<div class="chat_dialog">
    {{-- 左侧导航 --}}
    <div class="chat_left_wrap">
        <div class="chat_left" id="chat_left_scroll">
            <ul id="root_list">
                <li @if($type == 1)class="current_room"@endif data-type="1" id="chat_comments">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-side-msg"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span">评论的</span>
                        <div class="last_content"></div>
                    </div>
                </li>
                <li @if($type == 2)class="current_room"@endif data-type="2" id="chat_likes">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-side-like"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span">赞过的</span>
                        <div class="last_content"></div>
                    </div>
                </li>
                <li @if($type == 3)class="current_room"@endif data-type="3" id="chat_notifications">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-side-notice"></use>
                        </svg>
                    </div>
                    <div class="chat_item">
                        <span class="chat_span chat_span_noinfo">通知</span>
                    </div>
                </li>
                <li @if($type == 4)class="current_room"@endif data-type="4" id="chat_pinneds">
                    <div class="chat_left_icon">
                        <svg class="icon chat_svg" aria-hidden="true">
                            <use xlink:href="#icon-side-auth"></use>
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
    <div class="chat_right @if($type == 0) hide @endif" id="message_wrap">
        <div class="body_title">评论的</div>

        {{-- 审核通知 --}}
        <div class="audit_top hide">
            <div data-value="" class="zy_select t_c gap12 message_select">
                <span>动态评论置顶</span>
                <ul>
                    <li data-value="4" class="active">动态评论置顶</li>
                    <li data-value="5">文章评论置顶</li>
                    <li data-value="6">帖子评论置顶</li>
                    <li data-value="7">帖子置顶</li>
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
    <div class="chat_right @if($type != 0) hide @endif" id="chat_wrap">
        <div class="body_title"></div>

        <div class="chat_content_wrap chat_height">
            <div class="chat_content" id="chat_scroll">
                <div class="chat_cont" id="chat_cont">

                </div>
            </div>
        </div>

        <div class="chat_bottom">
            <textarea placeholder="输入文字, ctrl+enter发送" class="chat_textarea" id="chat_text"></textarea>
            <span class="chat_send" onclick="easemob.sendMes({{ $cid }}, {{ $uid }})" id="chat_send">发送</span>
        </div>
    </div>
</div>

<script type="text/javascript">
    var type = {{ $type }};
    var body_title = $('.body_title');
    var audit_top = $('.audit_top');
    var select = $(".message_select");
    $(function () {
        axios.defaults.baseURL = TS.SITE_URL;
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

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

            messageData($(this).data("value"));
        });

        $(document).click(function() {
            select.removeClass("open");
        });

        // 切换消息类型
        $('#root_list').on('click', 'li', function () {
            $(this).hasClass("current_room") || ($(this).addClass("current_room").siblings('.current_room').removeClass('current_room'));
            var type = $(this).data('type');

            if (type == 0) {
                body_title.removeClass('hide');
                $('#message_wrap').hide();
                $('#chat_wrap').show();
                var cid = $(this).data('cid');
                var uid = $(this).data('uid');
                $('#chat_send').attr('onclick', 'easemob.sendMes(' + cid + ', ' + uid + ')');

                // 设置为已读
                easemob.cid = cid;
                easemob.setRead(1, cid);
                easemob.listMes(cid, uid);
            } else {
                easemob.cid = 0;
                $('#message_wrap').show();
                $('#chat_wrap').hide();
                messageData(type);
            }
        });

        // 设置未读数量
        for (var i in TS.UNREAD) {
            if (TS.UNREAD[i] > 0) {
                $('#chat_' + i + ' .chat_unread_div').remove();
                $('#chat_' + i).prepend(easemob.formatUnreadHtml(1, TS.UNREAD[i]));
            }
        }

        // 设置房间
        easemob.cid = {{ $cid or 0 }};
        easemob.setInnerCon();

        // 加载内容
        if(type != 0) messageData(type);
    });
    // 获取消息列表
    function messageData(type) {
        $('#message_cont').html('');
        var title = '';
        switch(type) {
            case 1: // 评论
                title = '评论的';
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/comments',
                    params: {limit: 20},
                    loadtype: 2,
                    callback: function(){
                        easemob.setRead(0, 'comments');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 2: // 点赞
                title = '点赞的';
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/likes',
                    params: {limit: 20},
                    loadtype: 2,
                    callback: function(){
                        easemob.setRead(0, 'likes');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 3: // 通知
                title = '通知';
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/notifications',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2,
                    callback: function(){
                        easemob.setRead(0, 'notifications');
                    }
                });
                body_title.html(title);
                body_title.removeClass('hide');
                audit_top.addClass('hide');

                break;
            case 4: // 动态评论置顶审核
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedFeedComment',
                    params: {limit: 20},
                    loadtype: 2
                });
                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 5: // 资讯评论置顶审核
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedNewsComment',
                    params: {limit: 20},
                    loadtype: 2
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 6: // 帖子评论置顶审核
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedPostComment',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
            case 7: // 帖子置顶审核
                scroll.init({
                    container: '#message_cont',
                    loading: '.message_cont',
                    url: '/message/pinnedPost',
                    paramtype: 1,
                    params: {limit: 20},
                    loadtype: 2
                });

                body_title.addClass('hide');
                audit_top.removeClass('hide');
                break;
        }
    }
</script>