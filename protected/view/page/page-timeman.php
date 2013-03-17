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

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
            </div>
        </div>
    </form>
</div>

<div class="clear clear_fix bigblock">
    <table id="result" class="grid" >
        <thead height="50px">
            <tr>
                <th align=center width="150px">Время ожидания</th>
                <th>0 - 15</th>
                <th>15 - 30</th>
                <th>30 - 45</th>
                <th>45 - 60</th>
                <th>60 - 90</th>
                <th>90 - 120</th>
                <th>120 - 180</th>
                <th>180 - +</th>
                <th align=center width=100px>Среднее</th>
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
