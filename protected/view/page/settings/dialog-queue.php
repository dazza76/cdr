<?php
/**
 * dialog-queue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */


/*
  `name` varchar(128) NOT NULL,
  `musiconhold` varchar(128) DEFAULT NULL,
  `announce` varchar(128) DEFAULT NULL,
  `context` varchar(128) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `monitor_join` tinyint(1) DEFAULT NULL,
  `monitor_format` varchar(128) DEFAULT NULL,
  `queue_youarenext` varchar(128) DEFAULT NULL,
  `queue_thereare` varchar(128) DEFAULT NULL,
  `queue_callswaiting` varchar(128) DEFAULT NULL,
  `queue_holdtime` varchar(128) DEFAULT NULL,
  `queue_minutes` varchar(128) DEFAULT NULL,
  `queue_seconds` varchar(128) DEFAULT NULL,
  `queue_lessthan` varchar(128) DEFAULT NULL,
  `queue_thankyou` varchar(128) DEFAULT NULL,
  `queue_reporthold` varchar(128) DEFAULT NULL,
  `announce_frequency` int(11) DEFAULT NULL,
  `announce_round_seconds` int(11) DEFAULT NULL,
  `announce_holdtime` varchar(128) DEFAULT NULL,
  `retry` int(11) DEFAULT NULL,
  `wrapuptime` int(11) DEFAULT NULL,
  `maxlen` int(11) DEFAULT NULL,
  `servicelevel` int(11) DEFAULT NULL,
  `strategy` varchar(128) DEFAULT NULL,
  `joinempty` varchar(128) DEFAULT NULL,
  `leavewhenempty` varchar(128) DEFAULT NULL,
  `eventmemberstatus` tinyint(1) DEFAULT NULL,
  `eventwhencalled` tinyint(1) DEFAULT NULL,
  `reportholdtime` tinyint(1) DEFAULT NULL,
  `memberdelay` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `timeoutrestart` tinyint(1) DEFAULT NULL,

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



<script type="text/javascript">
    function showQueueDelete(uniqueid) {
        var $dialog = $('#dialog-queue-delete');
        $dialog.find('input[name=name]').val(uniqueid);
        $dialog.dialog('open');
    }

    $(document).ready(function() {
        $('#dialog-queue-delete').dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            minWidth: 350,
            minHeight: 70,
            buttons: {
                "OK": function() {
                    $('#dialog-queue-delete').find('form').eq(0).submit();
                },
                "Отмена": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>
<div id="dialog-queue-delete" class="dialog hidden edit-content" title="Удалить очередь">
    <form method="post">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="name" value="" />
        <div> Удалить очередь? </div>
    </form>
</div>