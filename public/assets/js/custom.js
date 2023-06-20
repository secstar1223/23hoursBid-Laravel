var current = $(window).scrollTop();
var total = $(window).height() + current;
var ele = $(".image-7");
var currPosition = ele.position().left + -400;
var trackLength = 900;
$(window).scroll(function (event) {
    current = $(window).scrollTop();
    var newPosition = trackLength * (current/total)
    ele.css({left:currPosition+newPosition+'px'});
});