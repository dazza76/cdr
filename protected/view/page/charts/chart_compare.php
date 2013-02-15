<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this QueueController */
?>


<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Тип</div>
            <div class="labeled">
                <select name="compareType" size="1"  default="<?php echo $this->compareType; ?>">
                    <option value="day">суточный</option>
                    <option value="week">недельный</option>
                    <option value="month">месячный</option>
                </select>
            </div>
        </div>



        <div class="filter fl_l sep">
            <div class="label">Дата сравнения</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" showweek="1" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" showweek="1" >
            </div>
        </div>
        <div class="filter fl_l but_search">
            <input type="submit" name="search" id="button-search" class="button" value="Показать" />
        </div>
        <input type="hidden" name="chart" value="<?php echo $this->chart; ?>" />
    </form>
</div>

<div class="clear clear_fix bigblock">
    <div style="width: 100%">
        <img src="chart.php?chart=<?php echo $this->compareType; ?>&fromdate=<?php echo $this->fromdate->format('Y-m-d'); ?>" alt="График">
        <br />
        <img src="chart.php?chart=<?php echo $this->compareType; ?>&fromdate=<?php echo $this->todate->format('Y-m-d'); ?>" alt="График">
    </div>
</div>