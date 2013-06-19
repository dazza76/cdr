<?php
//ac_dump($this->queueAgent);
/* @var $queueAgent QueueAgent */
$queueAgent = $this->queueAgent[0];
?>
<div class="ui-widget-content edit-content" style="margin-top: 10px; width: 550px;" >
    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Изменить оператора <?php echo html($queueAgent->agentid); ?></div>
    <form method="post">
        <input type="hidden" name="action" value="edit" />
        <input type="hidden" name="agentid" value="<?php echo html($queueAgent->agentid); ?>" />
<!--        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;">Номер оператора:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><?php echo html($queueAgent->agentid); ?></div>
        </div>-->

        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-required">*</span>ФИО:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="name" value="<?php echo html($queueAgent->name); ?>" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 1:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues1" value="<?php echo html($queueAgent->queues1); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 1:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty1" value="<?php echo html($queueAgent->penalty1); ?>" class="field-number" maxlength="2" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 2:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues2" value="<?php echo html($queueAgent->queues2); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 2:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty2" value="<?php echo html($queueAgent->penalty2); ?>" class="field-number" maxlength="2" /></div>
        </div>
        <div class="clear clear_fix mediumblock">
            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 3:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues3" value="<?php echo html($queueAgent->queues3); ?>" /></div>
        </div>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 3:</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty3" value="<?php echo html($queueAgent->penalty3); ?>" class="field-number" maxlength="2" /></div>
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