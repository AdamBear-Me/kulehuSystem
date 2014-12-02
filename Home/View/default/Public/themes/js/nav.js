$(function () {
    var st = 180;
    $('#nav_all>li').mouseenter(function () {
        $(this).find('ul').stop(false, true).slideDown(st);
		$(this).addClass("dqnav");
    }).mouseleave(function () {
        $(this).find('ul').stop(false, true).slideUp(st);
		$(this).removeClass("dqnav");
    });
});
