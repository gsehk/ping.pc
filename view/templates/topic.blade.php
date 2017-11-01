@if (!$data->isEmpty())
	@foreach ($data as $post)
		<div class="topic-item">
		    <div class="topic_l">
				<a href="{{ route('pc:topicinfo', $post->id) }}">
					<img src="{{ $post->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png') }}" alt="话题封面" width="140">
				</a>
		    </div>
		    <div class="topic_r">
				<a href="{{ route('pc:topicinfo', $post->id) }}">
					<p>{{ $post->name }}</p>
				</a>
		        <div>关注 <span id="tf-count-{{ $post->id }}">{{ $post->follows_count }}</span> 问题 <span>{{ $post->questions_count }}</span>
		        </div>
		        <div class="follow">
                    @if ($post->has_follow)
                        <a class="J-follow followed" tid="{{ $post->id }}"  status="1">已关注</a>
                    @else
                        <a class="J-follow" tid="{{ $post->id }}"  status="0">+关注</a>
                    @endif
                </div>
		    </div>
		</div>
	@endforeach
@elseif(isset($search) && $search)
	<div class="no_data_div">
		<div class="no_data">
			<img src="http://plus.cn/zhiyicx/plus-component-pc/images/pic_default_content.png">
			<p> 没有找到相关话题~</p>
			<div class="search-button">
				<a href="javascript:;" onclick="topic.show()">向官方建议创建新话题</a>
			</div>
		</div>
	</div>
	<script>
		var topic = {
		    show : function () {
		    	checkLogin();
                var html = '<form class="topic-show" id="topic-create">'
								+ '<p class="topic-title">建议创建话题</p>'
								+ '<div class="topic-from-row">'
									+ '<input type="text" name="name" placeholder="请输入话题名称">'
								+ '</div>'
								+ '<div class="topic-from-row">'
									+ '<textarea name="description" placeholder="请输入话题相关描述信息"></textarea>'
								+ '</div>'
					        + '</form>';
                ly.alert(html, '提交', function(){
		            var data = $('#topic-create').serializeArray();
		            $.ajax({
		                url: '/api/v2/user/question-topics/application',
		                type: 'POST',
		                data: data,
		                dataType: 'json',
		                error: function(xml) {
		                	layer.closeAll();
		                    noticebox(xml.responseJSON.message, 0);
		                },
		                success: function(res, data, xml) {
		                	layer.closeAll();
		                	if (xml.status == 201) {
		                		noticebox('申请成功', 1);
		                	} else {
		                		noticebox(res.message, 0);
		                	}
		                }
		            });
	        	});    
            }
		};
	</script>
@endif