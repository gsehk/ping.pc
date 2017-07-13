
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
                        <div>缘分评论了我</div>
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
                        <div>缘分赞了我</div>
                    </div>
                </li>
                <li @if($type=='at')class="current_room"@endif data-type="at">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xiangguande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">提到我的</span>
                        <div>缘分提到了我</div>
                    </div>
                </li>
                <li class="room_item">
                    <div class="chat_left_icon">
                        <img src="{{ $routes['resource'] }}/images/avatar.png" class="chat_img" />
                    </div>
                    <div class="left_class">
                        <span class="chat_span">仰光</span>
                        <div>今天周五啦</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="chat_right message-body">
            
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	$('#root_list li').on('click', function(e){
	$('#root_list li').removeClass('current_room');
	$(this).addClass('current_room');
	if ($(this).hasClass('room_item')) {
		// 聊天室。。。
	} else {
		var type = $(this).data('type');
		getMsgBody(type);
	}
})

var close = function(){
	$('.close>a').on('click',function(){
		$('#msgbox-shield').remove();
	    $('#msgbox-main').attr('id', 'msgbox-remove');
	    setTimeout(function() {
	        $('#msgbox-remove').remove();
	    }, 1000);
	});
};
var getMsgBody = function(type){
	if (type) {
		$('.message-body').html(loadHtml);
		$.get('/webMessage/getBody/'+type, function(html) {
			var html = JSON.parse(html);
			$('.message-body').html(html);
			$("img.lazy").lazyload({effect: "fadeIn"});
			close();
		});
	}
};
getMsgBody('{{$type}}');
});
</script>