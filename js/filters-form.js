$(document).ready(function () {
    var $form = $(".filters form").first();

    // select
    $form.find('select').each(function () {
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
    $('select[multiple]').each(function () {
        var options = $(this).attr("default").toString().split(",");
        for (var i = 0; i < options.length; i++) {
            $(this).find("option[value='" + options[i] + "']").attr("selected", "selected");
        }
    });
    $('select[multiple]').find('option').text(function (index, text) {
        return text.replace(/^\s*/, '').replace(/\s*$/, '');
    }).end().dropdownchecklist({
        firstItemChecksAll: true,
        maxDropHeight: 400,
        width: 150,
        explicitClose: 'Закрыть',
        textFormatFunction: function (options) {
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
        onComplete: function (selector) {
            if (selector.options[0].selected) {
                $('select[name="' + selector.name + '"] option').removeAttr("selected");
            }
        }
    });
    $('select[multiple]').each(function () {
        if (this.selectedIndex == 0) $(this).find('option').removeAttr("selected");
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