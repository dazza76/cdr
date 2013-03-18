<?php
$queue = $this->dataQueues->fetchAssoc();
if ( ! $queue) {
    echo "запись не найдена";
    return;
}

$interface_1 = substr($queue['interface'], 0,
                      strpos($queue['interface'], '(SIP/'));
$interface_2 = substr($queue['interface'],
                      strpos($queue['interface'], '(SIP/') + 5, -1);

$paused = ($queue['paused']) ? "checked=\"checked\"" : "";
?>

<script type="text/javascript">
    $(document).ready(function() {
        var $form = $('#form-queue-edit');
        var fields = {
            queue_name: $form.find('input[name=queue_name]'),
            interface_1: $form.find('input[name=interface_1]'),
            interface_2: $form.find('input[name=interface_2]'),
            uniqueid: $form.find('input[name=uniqueid]')
        };
        $form.submit(function() {
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
                $(this).submit();
            }
            return false;
        });

    });
</script>
<div class="ui-widget-content edit-content" style="margin-top: 10px; width: 550px;" >
    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Изменить очередь</div>
    <form method="post" id="form-queue-edit">
        <input type="hidden" name="action" value="edit" />
        <input type="hidden" name="uniqueid" value="<?php echo html($queue['uniqueid']); ?>" />
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Имя очереди:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="queue_name" value="<?php echo html($queue['queue_name']); ?>" maxlength="128" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>Интерфейс:</div>
            <div class="labeled fl_l">
                <div class="fl_l" style="margin-right: 5px;"><input type="text" style="width: 100px;" name="interface_1" value="<?php echo html($interface_1); ?>" maxlength="100" /></div>
                <div class="fl_l" style="padding-top: 5px;"><b>(SIP/</b></div>
                <div class="fl_l" style="margin-left: 5px;"><input type="text" style="width: 50px;" name="interface_2" value="<?php echo html($interface_2); ?>" maxlength="4" /></div>
                <div class="fl_l" style="padding-top: 5px;"><b>)</b></div>
            </div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r">Пенальти:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="penalty" value="<?php echo html($queue['penalty']); ?>" class="field-number" maxlength="11" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>uniqueid:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="uniqueid_new" value="<?php echo html($queue['uniqueid']); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="padding-top: 0px;">Paused:</div>
            <div class="labeled fl_l"><input type="checkbox" name="paused" value="1" <?php echo $paused; ?> /></div>
        </div>


        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;">-</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;">
                <button class="button">Сохранить</button>
            </div>
        </div>
        <div class="clear clear_fix bigblock"></div>
    </form>
</div>