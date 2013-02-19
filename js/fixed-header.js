$(document).ready(function () {
    if (!$('body').hasClass('fixed-header')) {
        return;
    }

    var $header = $("#header");
    $('.filters, .control-bar').width(function () { // Keeping filters width fixed
        if ($(this).width() < $('#container').width()) {
            return $(this).width();
        }
    }).appendTo($header);

    var $placeholder = $header.wrap('<div class="fixed-placeholder"/>').parent();
    var $view = $(window);

    $placeholder.height($placeholder.height());
    $header.addClass('fixed');

    $view.bind('resize', function () {
        $placeholder.height($header.outerHeight(true));
    }).bind('scroll resize', function () {
        var viewTop = $view.scrollTop();
        var placeholderTop = $placeholder.offset().top;
        if ((viewTop > placeholderTop) && !$header.hasClass('fixed-now')) {
            $header.addClass('fixed-now');
        } else if ((viewTop <= placeholderTop) && $header.hasClass('fixed-now')) {
            $header.removeClass('fixed-now');
        }
    }).resize();
});