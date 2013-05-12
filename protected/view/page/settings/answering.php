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
<div class="ui-widget-content edit-content" style="margin-top: 10px; width: 650px;" >
    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Автоинформатор</div>
    <form method="post">
        <input type="hidden" name="action" value="edit" />

        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-required"></span>TIME_DOW:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="TIME_DOW" value="<?php echo html($options['TIME_DOW']); unset($options['TIME_DOW']);  ?>" /></div>
            <!--
            <div class="labeled fl_l" style="width: 300px; margin-left: 5px;">
                <div style="margin-left:5px" class="fl_l" >Пн.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Вт.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Ср.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Чт.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Пт.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Сб.<input type="checkbox" /></div>
                <div style="margin-left:5px" class="fl_l" >Вс.<input type="checkbox" /></div>
            </div>
            -->
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">TIME_FROM:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="TIME_FROM" value="<?php echo html($options['TIME_FROM']); unset($options['TIME_FROM']); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;">TIME_TILL:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="TIME_TILL" value="<?php echo html($options['TIME_TILL']); unset($options['TIME_TILL']); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">OUTBOUND_RELEASE_TIMEOUT:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="OUTBOUND_RELEASE_TIMEOUT" value="<?php echo html($options['OUTBOUND_RELEASE_TIMEOUT']); unset($options['OUTBOUND_RELEASE_TIMEOUT']); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;">NOTIFY_NUMBER1:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="NOTIFY_NUMBER1" value="<?php echo html($options['NOTIFY_NUMBER1']); unset($options['NOTIFY_NUMBER1']); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">NOTIFY_NUMBER2:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="NOTIFY_NUMBER2" value="<?php echo html($options['NOTIFY_NUMBER2']); unset($options['NOTIFY_NUMBER2']); ?>" /></div>
        </div>
        <?php
            foreach ($options as $key => $value) {
                echo "<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\" />".PHP_EOL;
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