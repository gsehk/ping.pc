<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/message.css')}}"/>

<div class="chat_dialog">
    <div class="chat_content">
        <div class="chat_left">
            <ul id="root_list">
                <li @if($type=='pl')class="current_room"@endif data-type="pl">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xuanzedui-copy-copy-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">评论的</span>
                        <div>{{isset($message['comment']) && $message['comment'].'评论了我'}}</div>
                    </div>
                </li>
                <li @if($type=='zan')class="current_room"@endif data-type="zan">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xihuande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">赞过的</span>
                        <div>{{isset($message['like']) && $message['like'].'赞了我'}}</div>
                    </div>
                </li>
                <li @if($type=='tz')class="current_room"@endif data-type="tz">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xihuande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">通知</span>
                        <div>{{isset($message['notification']) && $message['notification']}}</div>
                    </div>
                </li>

                {{--<li class="room_item">
                    <div class="chat_left_icon">
                        <img src="{{ $routes['resource'] }}/images/avatar.png" class="chat_img" />
                    </div>
                    <div class="left_class">
                        <span class="chat_span">仰光</span>
                        <div>今天周五啦</div>
                    </div>
                </li>--}}
            </ul>
        </div>
        <div class="chat_right message-body">
            <div class="body-title">评论的</div>
            <div class="message-content">
                <div class="message-content-inner" id="message_content">

                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>

<script type="text/javascript">
    $(function () {
        getData('pl');

        // 切换消息类型
        $('#root_list').on('click', 'li', function () {
            $(this).hasClass("current_room") || ($(this).addClass("current_room").siblings('.current_room').removeClass('current_room'));
            var type = $(this).data('type');

            getData(type);
        });

        /**
         * 获取消息列表
         * @param type
         * @param limit
         * @param offset
         */
        function getData(type) {
            $('#message_content').html('');
            var title = '';
            switch(type) {
                case 'pl': // 评论加载
                    title = '评论的';
                    scroll.init({
                        container: '#message_content',
                        loading: '.message-content-inner',
                        url: '/webmessage/comments',
                        params: {limit: 10}
                    });

                    break;
                case 'zan': // 点赞加载
                    title = '点赞的';
                    scroll.init({
                        container: '#message_content',
                        loading: '.message-content-inner',
                        url: '/webmessage/likes',
                        params: {limit: 10}
                    });

                    break;
                case 'tz': // 通知加载
                    title = '通知';
                    scroll.init({
                        container: '#message_content',
                        loading: '.message-content-inner',
                        url: '/webmessage/notifications',
                        setting: {loadtype: 1},
                        params: {limit: 10}
                    });

                    break;
            }

            $('.body-title').text(title);
        }

    });





















{{--$(function(){--}}
	{{--$('#root_list li').on('click', function(e){--}}
	{{--$('#root_list li').removeClass('current_room');--}}
	{{--$(this).addClass('current_room');--}}
	{{--if ($(this).hasClass('room_item')) {--}}
		{{--// 聊天室。。。--}}
	{{--} else {--}}
		{{--var type = $(this).data('type');--}}
		{{--getMsgBody(type);--}}
	{{--}--}}
{{--})--}}

{{--var getMsgBody = function(type){--}}
	{{--if (type) {--}}
		{{--$('.message-body').html(loadHtml);--}}
		{{--$.get('/webMessage/getBody/'+type, function(html) {--}}
			{{--var html = JSON.parse(html);--}}
			{{--$('.message-body').html(html);--}}
			{{--$("img.lazy").lazyload({effect: "fadeIn"});--}}
		{{--});--}}
	{{--}--}}
{{--};--}}
{{--getMsgBody('{{$type}}');--}}
{{--});--}}
</script>