/**
 * input element
 */
$(function() {
    $(".button-refresh").click(function() {
        location.reload(true);
    });
    $("input:checkbox").each(function() {
        if ($(this).attr("default") == "1") {
            $(this).attr("checked", "checked");
        }
    });
    $("input:text, input:hidden, select[multiple!='multiple']").each(function() {
        var val = $(this).attr("default");
        if ($(this).attr("default")) {
            $(this).val(val);
        }
    });
    $("input.field-number").keypress(function(e) {
        if (e.which >= 48 && e.which <= 57) {
            return true;
        } else {
            return false;
        }
    });
});

/**
 * filters form
 */
$(document).ready(function() {
    var $form = $(".filters form").first();

    // select
    $form.find("select[multiple!='multiple']").each(function() {
        var v = $(this).attr("default");
        if (v) {
            $(this).find("[value='" + v + "']").attr("selected", "selected");
        } else {
            $(this).find("option:first").attr("selected", "selected");
        }
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
        $form.find("input[name=offset]").val('');
        $form.find('input').each(function() {
            if ($.trim($(this).val()) == '') {
                $(this).removeAttr('name');
            }
        });
        $form.find("select[multiple!='multiple']").each(function() {
            if ($(this).val() == '') {
                $(this).removeAttr('name');
            }
        });
        return true;
    });

    $("#button-search").click(function() {
        $form.find(".button").removeAttr('name');
        $("#export_type").removeAttr('name');
        //$form.removeAttr('target');
        $form.submit();
    });

    $("#button-export").click(function() {
        $("#export_type").attr('name', 'export');
//       $form.attr("target", "_blank");
        $form.submit();
        return false;
    });
});