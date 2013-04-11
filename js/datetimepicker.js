$(function() {
    $.timepicker.setDefaults({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
        dateFormat: "dd.mm.yy",
        buttonText: '',
        timeText: 'Время',
        hourText: 'Часы',
        minuteText: 'Минуты',
        secondText: 'Секунды',
        currentText: 'Сейчас',
        stepMinute: 10
    });


    var dtControl = {
        create: function(tp_inst, obj, unit, val, min, max, step) {
            $('<input class="ui-timepicker-input" value="' + val + '" style="width:90%">')
                .appendTo(obj)
                .spinner({
                min: min,
                max: max,
                step: step,
                change: function(e, ui) { // key events
                    // don't call if api was used and not key press
                    if (e.originalEvent !== undefined) tp_inst._onTimeChange();
                    tp_inst._onSelectHandler();
                },
                spin: function(e, ui) { // spin events
                    tp_inst.control.value(tp_inst, obj, unit, ui.value);
                    tp_inst._onTimeChange();
                    tp_inst._onSelectHandler();
                }
            });
            return obj;
        },
        options: function(tp_inst, obj, unit, opts, val) {
            if (typeof(opts) == 'string' && val !== undefined) return obj.find('.ui-timepicker-input').spinner(opts, val);
            return obj.find('.ui-timepicker-input').spinner(opts);
        },
        value: function(tp_inst, obj, unit, val) {
            if (val !== undefined) return obj.find('.ui-timepicker-input').spinner('value', val);
            return obj.find('.ui-timepicker-input').spinner('value');
        }
    };



    /** datetimepicker */
    $(".datetimepicker").datetimepicker({
        controlType: dtControl
    }).keydown(function() {
        $(this).datetimepicker("show");
        return false;
    }).focus(function() {
        $(this).datetimepicker("show");
    });

    // $(".datetimepicker").datetimepicker({
    //     controlType: dtControl
    // });
});