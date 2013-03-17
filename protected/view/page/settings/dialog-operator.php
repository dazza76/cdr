<?php
/* AC: v: */

/**
 * dialog-operator-add.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        var $dialog = $('#dialog-operator-add');
        var fields = {
            name: $dialog.find('input[name=name]'),
            agentid: $dialog.find('input[name=agentid]'),
            queues1: $dialog.find('input[name=queues1]'),
            penalty1: $dialog.find('input[name=penalty1]'),
            queues2: $dialog.find('input[name=queues2]'),
            penalty2: $dialog.find('input[name=penalty2]'),
            queues3: $dialog.find('input[name=queues3]'),
            penalty3: $dialog.find('input[name=penalty3]')
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
                    if (!fields.agentid.val()) {
                        fields.agentid.addClass("field-sucfail");
                        build = false;
                    } else {
                        fields.agentid.removeClass("field-sucfail");
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
<div id="dialog-operator-add" class="dialog hidden edit-content" title="Добавить оператора">
    <form method="post">
        <input type="hidden" name="action" value="add" />
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>ФИО:</div>
            <div class="labeled fl_l"><input type="text" name="name" value="" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Номер оператора:</div>
            <div class="labeled fl_l"><input type="text" name="agentid" value="" class="field-number" maxlength="11" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Список очередей пенальти 1:</div>
            <div class="labeled fl_l"><input type="text" name="queues1" value="" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r">Пенальти в очередях 1:</div>
            <div class="labeled fl_l"><input type="text" name="penalty1" value="" class="field-number" maxlength="2" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Список очередей пенальти 2:</div>
            <div class="labeled fl_l"><input type="text" name="queues2" value="" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r">Пенальти в очередях 2:</div>
            <div class="labeled fl_l"><input type="text" name="penalty2" value="" class="field-number" maxlength="2" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Список очередей пенальти 3:</div>
            <div class="labeled fl_l"><input type="text" name="queues3" value="" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r">Пенальти в очередях 3:</div>
            <div class="labeled fl_l"><input type="text" name="penalty3" value="" class="field-number" maxlength="2" /></div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function showOperatorDelete(id) {
        var $dialog = $('#dialog-operator-delete');
        $dialog.find('input[name=agentid]').val(id);
        $dialog.dialog('open');
    }

    $(document).ready(function() {
        $('#dialog-operator-delete').dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            minWidth: 350,
            minHeight: 70,
            buttons: {
                "OK": function() {
                    $('#dialog-operator-delete').find('form').eq(0).submit();
                },
                "Отмена": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>
<div id="dialog-operator-delete" class="dialog hidden edit-content" title="Удалить оператора">
    <form method="post">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="agentid" value="0" />
        <div> Удалить оператора </div>
    </form>
</div>