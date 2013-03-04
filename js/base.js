function gotoPage(page) {
    document.location = page;
}

function refresh() {
    location.reload(true);
}

function $ajax(data, callback) {
    $.ajax({
        type: "POST",
        cache: false,
        data: data
    }).fail(function() {
        errorBox.Connect();
    }).done(function(result) {
        console.log("page: " + app.page.page + "; act: " + data.act + "; result: " + result);
        if (Object.prototype.toString.call(callback) !== '[object Function]') {
            return;
        }
        try {
            var r = JSON.parse(result);
//            console.log(r.response);
            if (typeof r.response !== 'undefined') {
                callback(r.response);
            }
            else {
                if (typeof r.error !== 'undefined') {
                    errorBox.error(r.error);
                }
                else {
                    errorBox.JSON();
                }
            }
        } catch (e) {
            //errorBox.JSON();
        }
    });
}

/**
 * UI component
 */
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

    $('.dialog').dialog({
        disabled: false,
        autoOpen: false,
        draggable: false,
        resizable: false,
        modal: true,
        closeOnEscape: false
    });
    $('.tabs').tabs();
    $('.button').button();
});

/*
var messageBox = {
    _$box: null,
    _init: function() {
        $("#message_box").dialog({
            minWidth: 400,
            minHeight: 70,
            title: "Dialog Title"
        });

        messageBox._$box = $("#message_box");
        if (!messageBox._$box) {
            console.log("Error:: class messageBox not init!");
        }
    },
    getBox: function() {
        if (!messageBox._$box) {
            messageBox._init();
        }
        messageBox._$box.dialog({
            minWidth: 400,
            title: "Сообщение",
            buttons: {
                "OK": function() {
                    $(this).dialog("close");
                }
            }
        });
        return messageBox._$box;
    },
    message: function(msg, close) {
        if (close) {
            $('.dialog').dialog('close');
        }
        return messageBox.getBox().html('<p>' + msg + '</p>').dialog("open");
    }
};

var errorBox = {
    _getBox: function() {
        var $box = messageBox.getBox();
        $box.dialog("option", "title", "Ошибка");
        return $box;
    },
    Connect: function() {
        errorBox._getBox().html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Ошибка ответа сервера. Проверьте соединение с сервером</p>').dialog("open");
    },
    JSON: function() {
        errorBox._getBox().html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Ответ сервера не соответствует ожидаемуму шаблону!</p>').dialog("open");
    },
    error: function(error) {
        errorBox._getBox().html('<p><b>' + error.errno + "</b> : " + error.error + '</p>').dialog("open");
    }
};
*/