<div class="search-user">
    <div class="search-user-input">
        <input type="text" name="name" id="input-name">
    </div>
    <div class="search-lists">
        <div class="lists-notice">专家列表</div>
        <div class="lists">

        </div>
    </div>
</div>
<script>
    // 关键字搜索用户
    var input = $('#input-name');
    var invitation = $('.invitation-a');
    var lists = $('.lists');

    input.on('keyup', function () {
        getUsers();
    });

    function getUsers() {
        var keyword = input.val();
        $.ajax({
            type: "POST",
            url: '/question/users',
            data: {
                keyword: keyword,
                topics: "{{$topics}}",
                ajax: 1,
                limit: 10
            },
            success: function(res) {
                if (res.status == true) {
                    lists.html(res.data.html);
                }
            }
        });
    }

    getUsers();

    // 点击邀请
    lists.on('click', '.invitation-a', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#invitation_user').html('已邀请：'+name)
        args.invitations = [id];
        ly.close();
    })
</script>