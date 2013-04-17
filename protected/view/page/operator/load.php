<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this OperatorController */
?>

<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input type="hidden" name="section" value="load" />
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

        <input type="hidden" name="sort" value="" />
        <input type="hidden" name="desc" value="" />
        <input type="hidden" name="offset" value="" />
    </form>
</div>


<div class="clear clear_fix">
    <table class="grid" style="width: 1000px;">
        <thead>
            <tr>
                <th style=""             >Оператор</th>
                <th style="width: 150px;">Количество вызовов</th>
                <th style="width: 150px;">Время разговоров, мин</th>
                <th style="width: 150px;">Ср. время разг., сек</th>
                <th style="width: 150px;">Ср. время разг., сек</th>
                <th style="width: 150px;">Исходящих</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->dataResult as $oper) { ?>
                <tr>
                    <td><?php echo $oper['oper']; ?></td>
                    <td><?php echo $oper['total']; ?></td>
                    <td><?php echo round($oper['time'] / 60, 2); ?></td>
                    <td><?php echo round($oper['time'] / $oper['total']); ?></td>
                    <td><?php echo $oper['avg_tr']; ?></td>
                    <td><?php echo $oper['calls']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

