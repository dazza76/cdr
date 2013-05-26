<?php
/**
 * answering.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
/*
  TIME_DOW=1,1,1,1,1,1,1 – с вск по сбт, 1—звоним, 0 – не звоним
  TIME_FROM=9
  TIME_TILL=21
  OUTBOUND_RELEASE_TIMEOUT=60 – время повторного вызова. В минутах.
  NOTIFY_NUMBER1=89219123244
  NOTIFY_NUMBER2=

 */

/* @var $this SettingsController */
$options = $this->options;
?>
<script type="text/javascript">
    $(document).ready(function() {
        var arr = $("#time_dow_str_days").val().split(',');
        var i = 0;
        $("#time_dow_check_days input").each(function() {
            if (arr[i] == "1") {
                $(this).attr("checked", "checked");
            }
            i++;
        });
        $("#time_dow_check_days input").change(function() {
            var c_arr = [];
            $("#time_dow_check_days input").each(function() {
                c_arr.push(($(this).attr("checked")) ? "1" : "0");
            });
            $("#time_dow_str_days").val(c_arr.join(","));
        });
    });
</script>
<div class="ui-widget-content edit-content" style="margin-top: 10px; width: 650px;" >
    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Автоинформатор</div>
    <form method="post">
        <input type="hidden" name="action" value="edit" />

        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-required"></span><span class="field-inf">TIME_DOW</span> Рабочии дни:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;">
                <input type="hidden" id="time_dow_str_days" name="TIME_DOW" value="<?php echo html($options['TIME_DOW']); unset($options['TIME_DOW']); ?>" />
                <div class="labeled fl_l" style="width: 300px; margin-left: 5px;" id="time_dow_check_days">
                    <div style="margin-left:5px" class="fl_l" >Вс.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Пн.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Вт.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Ср.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Чт.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Пт.<input type="checkbox" /></div>
                    <div style="margin-left:5px" class="fl_l" >Сб.<input type="checkbox" /></div>
                </div>
            </div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-inf">TIME_FROM</span> Начало дня:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="TIME_FROM" value="<?php echo html($options['TIME_FROM']); unset($options['TIME_FROM']); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-inf">TIME_TILL</span> Конец дня:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="TIME_TILL" value="<?php echo html($options['TIME_TILL']); unset($options['TIME_TILL']); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-inf">OUTBOUND_RELEASE_TIMEOUT</span> Время дозвона:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="OUTBOUND_RELEASE_TIMEOUT" value="<?php echo html($options['OUTBOUND_RELEASE_TIMEOUT']); unset($options['OUTBOUND_RELEASE_TIMEOUT']); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-inf">NOTIFY_NUMBER1</span> Номер сообщ.:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="NOTIFY_NUMBER1" value="<?php echo html($options['NOTIFY_NUMBER1']); unset($options['NOTIFY_NUMBER1']); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-inf">NOTIFY_NUMBER2</span> Номер сообщ.2:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="NOTIFY_NUMBER2" value="<?php echo html($options['NOTIFY_NUMBER2']); unset($options['NOTIFY_NUMBER2']); ?>" /></div>
        </div>
<?php
foreach ($options as $key => $value) {
    echo "<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\" />" . PHP_EOL;
}
?>
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;">-</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;">
                <button class="button">Сохранить</button>
            </div>
        </div>
        <div class="clear clear_fix bigblock"></div>
    </form>
</div>