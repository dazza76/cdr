<?php
/* AC: v: */

/**
 * dialog-event.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        var $dialog = $('#dialog-event-add');
        var fields = {
            name: $dialog.find('input[name=name]'),
            filename: $dialog.find('input[name=filename]'),
            value: $dialog.find('input[name=value]'),
        };
        $dialog.dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            minWidth: 550,
            minHeight: 70,
            buttons: {
                "OK": function() {
                    var build = true;
                    if (!fields.name.val()) {
                        fields.name.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.name.removeClass("field-sucfail");
                    }
                    if (!fields.filename.val()) {
                        fields.filename.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.filename.removeClass("field-sucfail");
                    }

                    // if (!fields.value.val()) {
                    //     fields.value.addClass("field-sucfail");
                    //     build = false;
                    // } else {
                    //     fields.value.removeClass("field-sucfail");
                    // }

                    if (build) {
                        $dialog.find('form').eq(0).submit();
                    }
                },
                "Отмена": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                for (var i in fields) {
                    fields[i].val('');
                    fields[i].removeClass("field-sucfail");
                }
            }
        });
    });
</script>
<div id="dialog-event-add" class="dialog hidden edit-content" title="Добавить оператора">
    <form method="post">
        <input type="hidden" name="action" value="add" />
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Причина срабатывания:</div>
            <div class="labeled fl_l"><input type="text" name="name" value="" autocomplete="off" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Имя модуля:</div>
            <div class="labeled fl_l"><input type="text" name="filename" value="" class="" maxlength="11" autocomplete="off" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Значение</div>
            <div class="labeled fl_l"><input type="text" name="value" class="number" value="" autocomplete="off" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r">Регистрация события</div>
            <div class="labeled fl_l"><input type="radio" name="enabled" value="yes">Вкл.<input type="radio" name="enabled" value="no" checked="">Выкл.</div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Немедленно уведомлять</div>
            <div class="labeled fl_l"><input type="radio" name="urgent" value="yes">Вкл.<input type="radio" name="urgent" value="no" checked="">Выкл.</div>
        </div>

    </form>
</div>

<script type="text/javascript">
    function showEventDelete(id) {
        var $dialog = $('#dialog-event-delete');
        $dialog.find('input[name=id]').val(id);
        $dialog.dialog('open');
    }

    $(document).ready(function() {
        $('#dialog-event-delete').dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            minWidth: 350,
            minHeight: 70,
            buttons: {
                "OK": function() {
                    $('#dialog-event-delete').find('form').eq(0).submit();
                },
                "Отмена": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>
<div id="dialog-event-delete" class="dialog hidden edit-content" title="Удалить оператора">
    <form method="post">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" value="0" />
        <div> Удалить  событие?</div>
    </form>
</div>