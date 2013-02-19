$(document).ready(function () {
    var $form = $(".filters form").first();

    // select
    $form.find("select[multiple!='multiple']").each(function () {
        var v = $(this).attr("default");
        if (v) {
            $(this).find("[value='" + v + "']").attr("selected", "selected");
        } else {
            $(this).find("option:first").attr("selected", "selected");
        }
    });

    // checkbox
    $form.find(':checkbox').each(function () {
        if ($(this).attr("default")) {
            $(this).attr("checked", "checked");
        } else {
            $(this).removeAttr("checked");
        }
    });

    // submit
    $form.submit(function () {
        $form.find('select, input').each(function () {
            if (!$(this).val()) {
                $(this).removeAttr('name');
            }
        });
        $form.find("input[name=search]").removeAttr('name');
        return true;
    });
});