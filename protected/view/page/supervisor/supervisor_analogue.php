<?php
/**
 * supervisor_analogue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

/* @var $this SupervisorController */
?>

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
                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
            </div>
        </div>
    </form>
</div>


<div class="filters clear_fix bigblock of_h">
    <table class="grid">
        <thead>
            <tr height="20px">
                <th>Номер телефона</th>
                <th>Количество вызовов</th>
            </tr>
        </thead>
    </table>
</div>


<div class="clear clear_fix">
    <table class="grid" style="width: 300px;">
        <thead>
            <tr class="b-head">
                <th style="width: 150px;"> </th>
                <th style="width: 150px;"> </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->dataAnalogue as $row) { ?>
            <tr>
                <td><?php echo html($row['dst']); ?></td>
                <td><?php echo html($row['count']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
