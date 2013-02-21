function gotoPage(page) {
    document.location = page;
}

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
});




$(document).ready(function() {
    $('#operator_list a.icon-delete').click(function() {
        //mess
    });
});



