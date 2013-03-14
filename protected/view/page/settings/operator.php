<?php ?>
<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h" style="width: 660px">
        <div class="fl_l" style="padding-right: 15px;">
            Всего операторов: <?php echo @count($this->queueAgent); ?>
        </div>
        <div class="fl_r"><a onclick="$('#dialog-operator-add').dialog('open');" class="icon icon-add">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>


<div class="filters clear_fix mediumblock of_h">
    <table class="grid" style="width: 660px;">
        <thead>
            <tr>
                <th>ФИО</th>
                <th style="width: 100px;">Телефон</th>
                <th style="width: 70px;">Изменить</th>
                <th style="width: 70px;">Удалить</th>
            </tr>
        </thead>
    </table>
</div>


<div id="operator_list" class="clear clear_fix">
    <table class="grid" style="width: 660px;">
        <thead>
            <tr class="b-head">
                <th> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 70px;" > </th>
                <th style="width: 70px;" > </th>
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


<?php
include 'dialog-operator-add.php';
include 'dialog-operator-delete.php';
?>