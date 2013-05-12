<?php
include 'dialog-operator.php';
?>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input name="section" type="hidden" value="operator" />
        <div class="filter fl_l sep">
            <div class="label">ФИО</div>
            <div class="labeled">
                <input name="fio" type="text" value="<?php echo $this->fio; ?>" />
            </div>
        </div>

<!--        <div class="filter fl_l sep">
            <div class="label">Очередь</div>
            <div class="labeled">
                <?php echo Queue::showMultiple("queue[]", $this->queue); ?>
            </div>
        </div>-->

        <div class="filter fl_l sep">
            <div class="label">телефон</div>
            <div class="labeled">
                <input name="agent" type="text" value="<?php echo $this->agent; ?>" />
            </div>
        </div>

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>

        <input type="hidden" name="sort" value="<?php echo $this->sort; ?>" />
        <input type="hidden" name="desc" value="<?php echo $this->desc; ?>" />
        <input type="hidden" name="offset" value="<?php echo $this->offset; ?>" />
    </form>
</div>

<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h" style="width: 660px">
        <div class="fl_l" style="padding-right: 15px;">
            Всего операторов: <?php echo $this->count; ?>
        </div>
        <?php echo Utils::pagenator($this->count, $this->offset, 20); ?>
        <div class="fl_r" style="margin-right:15px;"><a onclick="$('#dialog-operator-add').dialog('open');" class="icon icon-add abut">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>


<div id="operator_list" class="clear clear_fix">
    <table class="grid" style="width: 660px;">
        <thead>
            <tr >
                <td class="head" > ФИО</td>
                <td class="head"  style="width: 100px;"> Телефон</td>
                <td class="head"  style="width: 70px;" > Изменить</td>
                <td class="head"  style="width: 70px;" > Удалить</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->queueAgent as $queueAgent) {
                /* @var $queueAgent QueueAgent */
                ?>
                <tr>
                    <td><?php echo html($queueAgent->name); ?></td>
                    <td align="center"><?php echo html($queueAgent->agentid); ?></td>
                    <td class="image-link"><a href="?section=operator&id=<?php echo $queueAgent->agentid; ?>" class="icon icon-edit"></a></td>
                    <td class="image-link"><a  onclick="showOperatorDelete(<?php echo html($queueAgent->agentid); ?>);" class="icon icon-delete"></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

