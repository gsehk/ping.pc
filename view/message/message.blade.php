<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/message.css')}}"/>

<div class="chat_dialog">
    <div class="chat_content">
        <div class="chat_left_wrap">
            <div class="chat_left">
                <ul id="root_list">
                    <li @if($type == 0)class="current_room"@endif data-type="0">
                        <div class="chat_left_icon">
                            <svg class="icon chat_img" aria-hidden="true">
                                <use xlink:href="#icon-xuanzedui-copy-copy-copy"></use>
                            </svg>
                        </div>
                        <div class="left_class">
                            <span class="chat_span">评论的</span>
                            <div>{{ $comments->count() > 0 ? $comments[0]->user->name.'评论了我' : '无人评论我' }}</div>
                            @if($counts['unread_comments_count'] > 0)
                                <span class="chat_num">{{ $counts['unread_comments_count'] }}</span>
                            @endif
                        </div>
                    </li>
                    <li @if($type == 1)class="current_room"@endif data-type="1">
                        <div class="chat_left_icon">
                            <svg class="icon chat_img" aria-hidden="true">
                                <use xlink:href="#icon-xihuande-copy"></use>
                            </svg>
                        </div>
                        <div class="left_class">
                            <span class="chat_span">赞过的</span>
                            <div>{{ $likes->count() > 0 ? $likes[0]->user->name.'赞了我' : '无人赞我' }}</div>
                            @if($counts['unread_likes_count'] > 0)
                                <span class="chat_num">{{ $counts['unread_likes_count'] }}</span>
                            @endif
                        </div>
                    </li>
                    <li @if($type == 2)class="current_room"@endif data-type="2">
                        <div class="chat_left_icon">
                            <svg class="icon chat_img" aria-hidden="true">
                                <use xlink:href="#icon-tongzhi2"></use>
                            </svg>
                        </div>
                        <div class="left_class">
                            <span class="chat_span chat_span_noinfo">通知</span>
                        </div>
                    </li>
                    <li @if($type == 3)class="current_room"@endif data-type="3">
                        <div class="chat_left_icon">
                            <svg class="icon chat_img" aria-hidden="true">
                                <use xlink:href="#icon-shenhetongzhi"></use>
                            </svg>
                        </div>
                        <div class="left_class">
                            <span class="chat_span chat_span_noinfo">审核通知</span>
                        </div>
                    </li>

                    @if (!empty($chat_list))
                    @foreach ($chat_list as $chat)
                    <li @if($user_id == $chat)class="current_room"@endif class="room_item" data-type="5" data-cid="{{ $chat['cid'] }}">
                        <div class="chat_left_icon">
                        <img src="{{ $chat['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" class="chat_img">
                        </div>
                        <div class="left_class">
                            <span class="chat_span">{{ $chat['user']['name'] }}</span>
                            <div></div>
                        </div>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="chat_right message-body">
            <div id="message_wrap">
                <div class="body-title"></div>

                <div class="audit-top hide">
                    <div data-value="" class="zy_select t_c gap12 message_select">
                        <span>动态评论置顶</span>
                        <ul>
                            <li data-value="3" class="active">动态评论置顶</li>
                            <li data-value="4">文章评论置顶</li>
                        </ul>
                        <i></i>
                    </div>
                </div>

                <div class="message-content">
                    <div class="message-content-inner" id="message_content">

                    </div>
                </div>
            </div>

            <div class="hide" id="chat_wrap">
                <div class="chat-content">
                    <div class="chat-content-inner" id="chat_content">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('zhiyicx/plus-component-pc/js/common.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/socket.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.message.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/dexie.js') }}"></script>

<script type="text/javascript">
    $(function () {
        var chat_list = <?php echo json_encode($chat_list) ?>;
        var cid = {{ $cid or 0 }};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + TOKEN,
                'Accept': 'application/json'
            }
        })

        var body_title = $('.body-title');
        var audit_top = $('.audit-top');
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
                message.listMessage();
            } else {
                $('#message_wrap').show();
                $('#chat_wrap').hide();
                getData(type);
            }
        });

        // 获取消息列表
        function getData(type) {
            $('#message_content').html('');
            var title = '';
            switch(type) {
                case 0: // 评论
                    title = '评论的';
                    scroll.init({
                        container: '#message_content',
                        loading: '.message-content-inner',
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
                        container: '#message_content',
                        loading: '.message-content-inner',
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
                        container: '#message_content',
                        loading: '.message-content-inner',
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
                        container: '#message_content',
                        loading: '.message-content-inner',
                        url: '/message/feedCommentTop',
                        params: {limit: 20},
                        loadtype: 2
                    });
                    body_title.addClass('hide');
                    audit_top.removeClass('hide');
                    break;
                case 4: // 资讯审核
                    scroll.init({
                        container: '#message_content',
                        loading: '.message-content-inner',
                        url: '/message/newsCommentTop',
                        params: {limit: 20},
                        loadtype: 2
                    });

                    body_title.addClass('hide');
                    audit_top.removeClass('hide');
                    break;
            }
        }

        // 初始化message
        message.init(chat_list, cid);
    });
</script>