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

<!--        <div class="filter fl_l sep">
            <div class="label">Год</div>
            <div class="labeled">
                <select name="year" size="1"  default="<?php echo $this->year; ?>">
                    <?php
                    for ($i = 2000; $i < 2013; $i ++ ) {
                        echo "<option value=\"{$i}\">{$i}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Месяц</div>
            <div class="labeled">
                <select name="month" size="1"  default="<?php echo $this->month; ?>">
                    <option value="1">январь</option>
                    <option value="2">февраль</option>
                    <option value="3">март</option>
                    <option value="4">апрель</option>
                    <option value="5">май</option>
                    <option value="6">июнь</option>
                    <option value="7">июль</option>
                    <option value="8">август</option>
                    <option value="9">сентябрь</option>
                    <option value="10">октябрь</option>
                    <option value="11">ноябрь</option>
                    <option value="12">декабрь</option>
                </select>
            </div>
        </div>-->

        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" >
            </div>
        </div>

        <div class="filter fl_l but_search">
            <input type="submit" name="search" id="button-search" value="Показать" />
        </div>
        <input type="hidden" name="chart" value="<?php echo $this->chart; ?>" />
    </form>
</div>

<div class="clear clear_fix bigblock">
    <div style="width: 100%">
        <img src="chart.php?chart=month&fromdate=<?php echo $this->fromdate->format('Y-m-d'); ?>" alt="График">
    </div>
</div>