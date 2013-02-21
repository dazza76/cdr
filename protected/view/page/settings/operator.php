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


<div id="operator_list" class="clear clear_fix mediumblock">
    <table class="grid" style="width: 660px;">
        <thead>
            <tr>
                <th>ФИО</th>
                <th style="width: 100px;">Телефон</th>
                <th style="width: 70px;">Изменить</th>
                <th style="width: 70px;">Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->queueAgent as $queueAgent) {
                /* @var $queueAgent QueueAgent */
                ?>
                <tr>
                    <td><?php echo html($queueAgent->name); ?></td>
                    <td><?php echo html($queueAgent->agentid); ?></td>
                    <td class="image-link"><a href="#" class="icon icon-edit"></a></td>
                    <td class="image-link"><a onclick="" class="icon icon-delete"></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php            include 'dialog-operator-add.php'; ?>