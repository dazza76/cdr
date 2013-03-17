<?php
/**
 * dialog-queue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        var $dialog = $('#dialog-queue-add');
        var fields = {
            queue_name: $dialog.find('input[name=queue_name]'),
            interface_1: $dialog.find('input[name=interface_1]'),
            interface_2: $dialog.find('input[name=interface_2]'),
            penalty: $dialog.find('input[name=penalty]'),
            uniqueid: $dialog.find('input[name=uniqueid]'),
            paused: $dialog.find('input[name=paused]')
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
                    if (!fields.queue_name.val()) {
                        fields.queue_name.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.queue_name.removeClass("field-sucfail");
                    }

                    if (!fields.interface_1.val() || !fields.interface_2.val()) {
                        fields.interface_1.addClass("field-sucfail");
                        fields.interface_2.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.interface_1.removeClass("field-sucfail");
                        fields.interface_2.removeClass("field-sucfail");
                    }

                    if (!fields.uniqueid.val()) {
                        fields.uniqueid.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.uniqueid.removeClass("field-sucfail");
                    }

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

<div id="dialog-queue-add" class="dialog hidden edit-content" title="Добавить запись">
    <form method="post">
        <input type="hidden" name="action" value="add" />
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Имя очереди:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="queue_name" value="" maxlength="128" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Интерфейс:</div>
            <div class="labeled fl_l">
                <div class="fl_l" style="margin-right: 5px;"><input type="text" style="width: 100px;" name="interface_1" value="" maxlength="100" /></div>
                <div class="fl_l" style="padding-top: 5px;"><b>(SIP/</b></div>
                <div class="fl_l" style="margin-left: 5px;"><input type="text" style="width: 50px;" name="interface_2" value="" maxlength="4" /></div>
                <div class="fl_l" style="padding-top: 5px;"><b>)</b></div>
            </div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Пенальти:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="penalty" value="" class="field-number" maxlength="11" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>uniqueid:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="uniqueid" value="" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="padding-top: 0px;">Paused:</div>
            <div class="labeled fl_l"><input type="checkbox" name="paused" value="1" /></div>
        </div>
    </form>
</div>