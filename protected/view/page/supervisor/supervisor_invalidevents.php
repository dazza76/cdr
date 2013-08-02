<?php
/**
 * supervisor_invalidevents.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

/* @var $this SupervisorController */
?>
<script type="text/javascript">
    var pageOptions = {
        section: 'invalidevents',
        onUpdate: 0
    };
</script>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input type="hidden" name="section" value="invalidevents" />
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Оператор</div>
            <div class="labeled">
                <select name="oper" size="1"  default="<?php echo $this->oper; ?>">
                    <?php echo QueueAgent::showOperslist(); ?>
                </select>
            </div>
        </div>


        <div class="filter fl_l sep">
            <div class="label">Очередь</div>
            <div class="labeled">
                <?php echo Queue::showMultiple("queue[]", $this->queue); ?>
            </div>
        </div>


        <div class="filter fl_l sep">
            <div class="label">События</div>
            <div class="labeled">
                <select name="event[]"  size="1" multiple="multiple">
                    <option value=" " >Все события</option>
                    <?php foreach ($this->eventsArr as $value) {
                        echo "<option value=\"{$value['id']}\" >{$value['filename']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>

<!--
        <div class="filter fl_l sep">
            <div class="label">Формат экспорта</div>
            <div class="labeled">
                <select id="export_type" name="export">
                    <option value="csv">CSV</option>
                    <option value="xls">XLS</option>
                </select>
                <input type="submit" id="button-export" class="button" value="Экспорт" />
            </div>
        </div>
 -->

    </form>
</div>





<div class="clear clear_fix">
    <table class="grid" style="width: 1200px;">
        <thead>
            <tr>
                <td class="head"  style="width: 150px;">Время регистрации</td>
                <td class="head"  style="width: 150px;">Допустивший оператор</td>
                <td class="head"  style="">Событие (пороговое значение)</td>
                <td class="head"  style="width: 150px;">Очередь</td>
            </tr>
        </thead>
        <tbody>
            <?php
            while($row = $this->dataResult->fetchAssoc()) {
                ?>
            <tr>
                <td><?php echo html($row['dateofevent']); ?></td>
                <td><?php echo QueueAgent::getOper($row['agentid']); ?></td>
                <td><?php echo html($row['name']."(".$row['value'].")"); ?><br /><small><?php echo html($row['reason']); ?></small></td>
                <td> - </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
