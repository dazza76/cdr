function gotoPage(page) {
    document.location = page;
}


/*********
 * UI
 *********/
$(document).ready(function() {
    /** datepicker */
    $.datepicker.setDefaults($.extend($.datepicker.regional["ru"]));
    $(".datepicker").datepicker({
        showAnim: "fadeIn",
        showOn: "button",
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd"
    }).keydown(function() {
        $(this).datepicker("show");
        return false;
    }).focus(function() {
        $(this).datepicker("show");
    });
    $(".datepicker[showweek=1]").datepicker("option", "showWeek", true);

    /** datetimepicker */
    $(".datetimepicker").datetimepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
        dateFormat: "yy-mm-dd",
        buttonText: '',
        timeText: 'Время',
        hourText: 'Часы',
        minuteText: 'Минуты',
        secondText: 'Секунды',
        currentText: 'Сейчас',
        stepMinute: 10
    }).keydown(function() {
        $(this).datetimepicker("show");
        return false;
    }).focus(function() {
        $(this).datetimepicker("show");
    });


    $(".button").button();


    /**
     * JPlayer
     */
    $("#jp_container_1").css({
        top: "-1000px",
        left: "-1000px"
    });
    $(".player_button").click(function() {
        var $player = $("#jquery_jplayer");
        $(".player_button").removeClass("b_pause").toggleClass("b_play", true);

        if ($(this).attr("play") === "play") {
            $(this).attr("play", "pause");
            $(this).toggleClass("b_play", true);
            $player.jPlayer("pause");
        } else if ($(this).attr("play") === "pause") {
            $(this).attr("play", "play");
            $(this).toggleClass("b_pause", true);
            $player.jPlayer("play");
        } else {
            $player.jPlayer("stop");
            var uniqueid = $(this).find("input").val();
            var file = "http://" + document.domain + rec.directory + uniqueid + "." + rec.format;
            var setMedia = {};
            setMedia[rec.format] = file;

            $player.jPlayer("setMedia", setMedia);
            console.log(file);

            $(".player_button").removeAttr("play");

            $(this).attr("play", "play");
            $(this).toggleClass("b_pause", true);

            var $slider = $(this).parent().find(".slider").first();
            $("#jp_container_1").css({
                top: $slider.position().top + 8,
                left: $slider.position().left + 7
            });
            $player.jPlayer("play");
        }
    });
});


/**
 * form
 */
$(document).ready(function() {
    var $form = $(".filters form").first();

    // select
    $form.find('select').each(function() {
        if ($(this).attr('multiple')) {
            return;
        }

        var v = $(this).attr("default");
        if (v) {
            $(this).find("[value='" + v + "']").attr("selected", "selected");
        } else {
            $(this).find("option:first").attr("selected", "selected");
        }
    });

    // select[multiple]
    $('select[multiple]').each(function() {
        var options = $(this).attr("default").toString().split(",");
        for (var i = 0; i < options.length; i++) {
            $(this).find("option[value='" + options[i] + "']").attr("selected", "selected");
        }
    });
    $('select[multiple]').find('option').text(function(index, text) {
        return text.replace(/^\s*/, '').replace(/\s*$/, '');
    }).end().dropdownchecklist({
        firstItemChecksAll: true,
        maxDropHeight: 400,
        width: 150,
        explicitClose: 'Закрыть',
        textFormatFunction: function(options) {
            console.log('test');
            var selectedOptions = options.filter(":selected");
            var countOfSelected = selectedOptions.size();
            var size = options.size();
            var allText = options.filter(":first").text();
            switch (countOfSelected) {
                case 0:
                    return "Выберите значения";
                case 1:
                    return selectedOptions.text();
                case options.size():
                    return allText;
                default:
                    return "Выбрано: " + countOfSelected;
            }
        },
        onComplete: function(selector) {
            if (selector.options[0].selected) {
                $('select[name="' + selector.name + '"] option').removeAttr("selected");
            }
        }
    });
    $('select[multiple]').each(function() {
        if (this.selectedIndex == 0)
            $(this).find('option').removeAttr("selected");
    });

    // checkbox
    $form.find(':checkbox').each(function() {
        if ($(this).attr("default")) {
            $(this).attr("checked", "checked");
        } else {
            $(this).removeAttr("checked");
        }
    });

    // submit
    $form.submit(function() {
        $form.find('select, input').each(function() {
            if (!$(this).val()) {
                $(this).removeAttr('name');
            }
        });
        $form.find("input[name=search]").removeAttr('name');
        return true;
    });

});


/*********
 * filters panel
 *********/
$(document).ready(function() {
    if (!$('body').hasClass('fixed-header')) {
        return;
    }

    var $header = $("#header");
    $('.filters, .control_bar').width(function() { // Keeping filters width fixed
        if ($(this).width() < $('#container').width()) {
            return $(this).width();
        }
    }).appendTo($header);

    var $placeholder = $header.wrap('<div class="fixed-placeholder"/>').parent();
    var $view = $(window);

    $placeholder.height($placeholder.height());
    $header.addClass('fixed');

    $view.bind('resize', function() {
        $placeholder.height($header.outerHeight(true));
    }).bind('scroll resize', function() {
        var viewTop = $view.scrollTop();
        var placeholderTop = $placeholder.offset().top;
        if ((viewTop > placeholderTop) && !$header.hasClass('fixed-now')) {
            $header.addClass('fixed-now');
        } else if ((viewTop <= placeholderTop) && $header.hasClass('fixed-now')) {
            $header.removeClass('fixed-now');
        }
    }).resize();
});



/*********
 * result table
 *********/
$(document).ready(function() {
    var $table = $(".grid");
    if (!$table.length) {
        return;
    }

    $table.find("thead th.sortable[data-sort]").each(function() {
        var txt = ($(this).attr("data-sort") == "desc") ? "&nbsp;▼" : "&nbsp;▲";
        txt = $(this).html() + txt;
        $(this).html(txt);
    });

    $table.find("thead th.sortable").click(function() {
        var sort = $(this).attr("data-column");
        var desc = $(this).attr("data-sort");  //.toString();
        $(".filters form input[name=offset]").val(0);

        desc = (desc == 'asc') ? 1 : '';
        $(".filters form input[name=sort]").val(sort);
        $(".filters form input[name=desc]").val(desc);
        $(".filters form").submit();
    });



    /**
     * edit comment control
     */
    var $cedit = $("#comment_edit");
    var $editBox = $("#comment_edit .edit_box").first();
    var rowComment = {
        id: null,
        comment: "",
        $span: null,
        initRow: function(id, span) {
            rowComment.id = id;
            rowComment.$span = span;
            rowComment.comment = $(span).text().toString();
        },
        editRow: function(str) {
            var $edit_span = rowComment.$span;
            var edit_id = rowComment.id;
            str = $.trim(str);
            if (!rowComment.comment) {
                rowComment.comment = "";
            }
            if (!str) {
                str = "";
            }
            if (rowComment.comment === str || !rowComment.id) {
                console.log("comment cancel");
                return;
            }
            $edit_span.text("loading...");
            var data = {
                act: "editComment",
                id: rowComment.id,
                comment: str
            };
            $.ajax({
                type: "POST",
                cache: false,
                data: data
            }).done(function(result) {
                console.log("comment save: " + edit_id);
                $edit_span.text(str);
            }).fail(function() {
                $edit_span.text("error");
                console.log("CONNECT ERROR");
            });
        }
    };

    $editBox.keydown(function(evt) {
        // enter = 13
        // esc = 27
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode === 13) {
            $editBox.blur();
            return false;
        }
        if (charCode === 27) {
            rowComment.id = null;
            $editBox.blur();
            return false;
        }
        return true;
    });
    $editBox.blur(function() {
        var str = $editBox.val().toString();
        rowComment.editRow(str);
        rowComment.id = null;
        $editBox.val("");
        $cedit.hide();
    });
    $table.find("tbody td.grid_edit").click(function() {
        var id = $(this).parent().attr("callid");
        var span = $(this).find("span");
        rowComment.initRow(id, span);

        $cedit.css({
            top: $(this).position().top,
            left: $(this).position().left,
            width: $(this).outerWidth(),
            height: $(this).outerHeight()
        }).show();
        $editBox.val(rowComment.comment);
        $editBox.focus();
    });

});