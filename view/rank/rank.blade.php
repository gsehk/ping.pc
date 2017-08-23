<div class="list_fans fans1">
    <div class="rank-tit">
        <span>{{$title}}</span>
        <span class="right">
            <i class="arrow-rank-l" id="{{$genre}}last" onclick="gorank({{isset($nowPage) ? $nowPage : 1}},'{{$genre}}',10)"></i>
              {{--<font id="postnum">1</font>/{{ceil($count/10)}}--}}
            <font id="{{$genre}}num" style="display:none">1</font>
            <i class="arrow-rank-r" id="{{$genre}}next" onclick="gorank({{(isset($nowPage) ? $nowPage : 1) + 1}},'{{$genre}}',10)"></i>
          </span>
    </div>
    <div class="list_pm fs-14">
        <span class="pm_1">排名</span>
        <span class="pm_2">昵称</span>
        @if(isset($tabName))
            <span class="pm_3">{{isset($tabName) ? $tabName : ''}}</span>
        @endif
    </div>
    <ul class="fans_ul" id="{{$genre}}-rank-list">
        @component('pcview::rank.lists', ['genre' => $genre, 'post' => $post, 'tabName' => (isset($tabName) ? $tabName : '')])
        @endcomponent
    </ul>
</div>
<script>
    function gorank(action,genre,num) {
        var current = $('div[rel="'+genre+'div"][current="1"]');
        //当前页数
        var curnum = $('#'+genre+'num').text();

        //向前
        if ( action == 1 ){
            curnum = parseInt(curnum) - 1;
        } else {
            //向后翻页
            curnum = parseInt(curnum) + 1;
        }
        var last = $('div[rel="'+genre+'div"][current="1"]').prev();
        var postArgs = {};
        postArgs.offset = (curnum - 1) * num;
        postArgs.limit = num;
        postArgs.genre = genre;
        if ( last != undefined ) {
            $.ajax({
                url: SITE_URL + '/rank/rankList',
                type: 'GET',
                data: postArgs,
                dataType: 'json',
                error: function (xml) {
                },
                success: function (res) {
                    if (res.status) {
                        if (res.data.count == 0) {
                            noticebox('已无更多啦', 0);
                        } else {
                            $('#'+genre+'-rank-list').html(res.data.html);
                            $('#'+genre+'num').text(curnum);
                        }
                        //res.after ? $('#'+genre+'next').show() : $('#'+genre+'next').hide();
                    }
                    return false;
                }
            });
        }
    }
</script>