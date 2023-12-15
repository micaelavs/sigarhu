var hidWidth;
var scrollBarWidths = 40;

var widthOfList = function () {
    var itemsWidth = 0;
    $('.list li').each(function () {
        var itemWidth = $(this).outerWidth();
        itemsWidth += itemWidth;
    });
    return itemsWidth;
};

var widthOfHidden = function () {
    return (($('.wrapper').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
};

var getLeftPosi = function () {
    return $('.list').position().left;
};

var reAdjust = function () {
    var flag = true;
    var itemsWidth = 0;
    $('.list li').each(function () {
        if (flag == true) {
            var itemWidth = $(this).outerWidth();
            itemsWidth += itemWidth;
            //            alert($(this).attr('class'));
            if ($(this).attr('class') == 'nav-item active') {
                flag = false;

            }
        }
    });
    if (($('.wrapper').outerWidth()) < itemsWidth) {
        $('.scroller-left').fadeIn('slow');
        $('.scroller-right').fadeOut('slow');

        $('.list').animate({ left: "+=" + widthOfHidden() + "px" }, 'slow', function () {

        });
    }
    else {
        $('.scroller-right').fadeIn('slow');
        $('.scroller-left').fadeOut('slow');

        $('.list').animate({ left: "-=" + getLeftPosi() + "px" }, 'slow', function () {

        });
    }
}
reAdjust();

$(window).on('resize', function (e) {
    reAdjust();
});

$('.scroller-right').click(function () {

    $('.scroller-left').fadeIn('slow');
    $('.scroller-right').fadeOut('slow');

    $('.list').animate({ left: "+=" + widthOfHidden() + "px" }, 'slow', function () {

    });
});

$('.scroller-left').click(function () {

    $('.scroller-right').fadeIn('slow');
    $('.scroller-left').fadeOut('slow');

    $('.list').animate({ left: "-=" + getLeftPosi() + "px" }, 'slow', function () {

    });
});