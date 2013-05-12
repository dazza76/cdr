<?php
/**
 * cdr.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this TimemanController */
?>

<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
            </div>
        </div>
        <div class="filter fl_l sep">
            <div class="label">Очереди</div>
            <div class="labeled">
                <?php echo Queue::showMultiple("queue[]", $this->queue); ?>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">мобильные</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="mob" type="checkbox" value="1" <?php if ($this->mob) echo "default=\"1\""; ?> />
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">VIP</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="vip" type="checkbox" value="1" <?php if ($this->vip) echo "default=\"1\""; ?> />
            </div>
        </div>


        <div class="filter fl_l ">
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

<div class="clear clear_fix bigblock">
    <table id="result" class="grid" >
        <thead height="50px">
            <tr>
                <td class="head"  align=center width="150px">Время ожидания</td>
                <td class="head" >0 - 15</td>
                <td class="head" >15 - 30</td>
                <td class="head" >30 - 45</td>
                <td class="head" >45 - 60</td>
                <td class="head" >60 - 90</td>
                <td class="head" >90 - 120</td>
                <td class="head" >120 - 180</td>
                <td class="head" >180 - +</td>
                <td class="head"  align=center width=100px>Среднее</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Принято</td>
                <td><?php echo $this->getComplete(0, 15); ?></td>
                <td><?php echo $this->getComplete(15, 30); ?></td>
                <td><?php echo $this->getComplete(30, 45); ?></td>
                <td><?php echo $this->getComplete(45, 60); ?></td>
                <td><?php echo $this->getComplete(60, 90); ?></td>
                <td><?php echo $this->getComplete(90, 120); ?></td>
                <td><?php echo $this->getComplete(120, 180); ?></td>
                <td><?php echo $this->getComplete(180, 32768); ?></td>
                <td><?php echo $this->getAvgComplete(); ?></td>
            </tr>
            <tr>
                <td>Потеряно</td>
                <td><?php echo $this->getAbandoned(0, 15); ?></td>
                <td><?php echo $this->getAbandoned(15, 30); ?></td>
                <td><?php echo $this->getAbandoned(30, 45); ?></td>
                <td><?php echo $this->getAbandoned(45, 60); ?></td>
                <td><?php echo $this->getAbandoned(60, 90); ?></td>
                <td><?php echo $this->getAbandoned(90, 120); ?></td>
                <td><?php echo $this->getAbandoned(120, 180); ?></td>
                <td><?php echo $this->getAbandoned(180, 32768); ?></td>
                <td><?php echo $this->getAvgAbandoned(); ?></td>
            </tr>
        </tbody>
    </table>
</div>
