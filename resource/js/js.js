$(function () {
    
    //$(window).SetResize(function () {
    //    var dyCenter = $(".dy_center").height() + 100 ;
    //    var dyRight = $(".dy_right").height();
    //    alert(dyCenter)
    //    $(".body_right").height = dyCenter;
    //})
    //$(function () {
    //    SetResize();
    //})
    //$(window).resize(function () {
    //    SetResize();
    //})
    //返回顶部
    $("#dy_2").click(function () {
        $("html,body").animate({ scrollTop: 0 }, 500);
    })
    $(".ex_show").click(function () {
        $(".ex_img").toggle();
    })
    .$(".inR_bottom_list").click(function () {
        $(this).css("border", "1px solid #59b6d7");
        ;
    });
})
