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
        console.log("[API] "  + data.act + " > " + result);
        if (Object.prototype.toString.call(callback) !== '[object Function]') {
            return;
        }
        try {
            var r = JSON.parse(result);
            //            console.log(r.response);
            if (typeof r.response !== 'undefined') {
                callback(r.response);
            } else {
                if (typeof r.error !== 'undefined') {
                    errorBox.error(r.error);
                } else {
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


    $(".spinner").spinner({
        spin: function(event, ui) {
            if (ui.value < 0) {
                $(this).spinner("value", 0);
                return false;
            } else if (ui.value > 10) {
                $(this).spinner("value", 10);
                return false;
            }
        }
    });


    $("input[type='text'].number").keypress(function(e) {
        if (e.which >= 48 && e.which <= 57) {
            return true;
        } else {
            return false;
        }
    });
});