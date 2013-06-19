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
        <input type="hidden" name="section" value="monthly" />
        <div class="filter fl_l sep">
            <div class="label">Месяц</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y'); ?>" class="datepicker" >
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

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>


        <div class="filter fl_r">
            <div class="labeled" style="margin-top: 24px;">
                <span>
                    <input type="hidden" id="export_type" name="export" value="1" />
                    <a id="button-export" href="" class="icon icon_excel">Экспорт</a>
                </span>
            </div>
        </div>

    </form>
</div>

<div class="clear clear_fix">
    <table class="grid" >
        <thead>
            <tr >
                <td class="head"  style=""             >Оператор</td>
                <td class="head"  style="width: 150px;">Входящие, шт.</td>
                <td class="head"  style="width: 150px;">Исходящие, шт.</td>
                <td class="head"  style="width: 150px;">Всего вызовов, шт.</td>
                <td class="head"  style="width: 150px;">Простой, ЧЧ:ММ:СС</td>
                <td class="head"  style="width: 150px;">Обработка, ЧЧ:ММ:СС</td>
                <td class="head"  style="width: 150px;">Перерыв, ЧЧ:ММ:СС</td>
                <td class="head"  style="width: 150px;">Долгое поднятие трубки, шт.</td>
                <td class="head"  style="width: 150px;">Ср. вр. разговора, сек.</td>
                <td class="head"  style="width: 150px;">Ср. вр. подн. трубки, сек.</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->dataResult as $row) { ?>
                <tr>
                    <td><?php echo $row['oper']; ?></td>
                    <td><?php echo (int) $row['src']; ?></td>
                    <td><?php echo (int) $row['dst']; ?></td>
                    <td><?php echo (int) $row['src'] + (int) $row['dst']; ?></td>
                    <td><?php echo $row['prost']; ?></td>
                    <td><?php echo $row['obrab']; ?></td>
                    <td><?php echo $row['perer']; ?></td>
                    <td><?php echo (int) $row['maxring']; ?></td>
                    <td><?php echo $row['callduration']; ?></td>
                    <td><?php echo $row['ringtime']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>