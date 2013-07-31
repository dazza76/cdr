<?php
/**
 * supervisor_analogue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

/* @var $this SupervisorController */
?>
<script type="text/javascript">
    var pageOptions = {
        section: 'analogue',
        onUpdate: 0
    };
</script>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input type="hidden" name="section" value="analogue" />
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
            </div>
        </div>

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>


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


    </form>
</div>





<div class="clear clear_fix">
    <table class="grid" style="width: 450px;"  >
        <thead>
            <tr>
                <td class="head"  style="width: 150px;">Номер телефона</td>
                <td class="head"  style="width: 150px;">Количество вызовов</td>
                <td class="head"  style="width: 150px;">Количество вызовов 2</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->dataAnalogue as $row) { ?>
            <tr>
                <td><?php echo html($row['dst']); ?></td>
                <td><?php echo html($row['count']); ?></td>
                <td><?php echo html($row['count2']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
