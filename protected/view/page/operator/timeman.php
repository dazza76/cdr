<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this OperatorController */

$tempfromdate = $this->fromdate->format('Y-m-d H:i').':00';
$temptodate = $this->todate->format('Y-m-d H:i').':00';

$queue_arr = $this->queue;
if(!$queue_arr) {
    $queue_arr = array_keys(Queue::getQueueArr());
}
foreach ($queue_arr as $queue) {
    $queues[]=App::Db()->quoteEscapeString($queue);
}
$queues = implode(',',$queues);
unset($queue_arr, $queue);


?>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input name="section" value="timeman" type="hidden" />
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
                <?php
                echo Queue::showMultiple("queue[]", $this->queue);
                ?>
            </div>
        </div>


        <div class="filter fl_l ">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>


        <!--         <div class="filter fl_r">
                    <div class="labeled" style="margin-top: 24px;">
                        <span>
                            <input type="hidden" id="export_type" name="export" value="1" />
                            <a id="button-export" href="" class="icon icon_excel">Экспорт</a>
                        </span>
                    </div>
                </div> -->


    </form>
</div>

<div class="clear clear_fix bigblock">

    <div class="tabs tabs-no-content">
        <ul>
            <li><a href="#tabs-1">Длительность поднятие трубки</a></li>
            <li><a href="#tabs-2">Длительность входящих</a></li>
            <li><a href="#tabs-3">Длительность исходящих</a></li>
            <li><a href="#tabs-4">Длительность перерывов</a></li>
            <li><a href="#tabs-5">Длительность поствызывных обработок</a></li>
        </ul>


        <div id="tabs-1">
            <table class="grid" style="width: 900px; ">
                <thead height="50px">
                    <tr>
                        <td class="head" align="center"             >Оператор</td>
                        <td class="head" align="center" width="70px">0-3</td>
                        <td class="head" align="center" width="70px">3-7</td>
                        <td class="head" align="center" width="70px">7-10</td>
                        <td class="head" align="center" width="70px">10-20</td>
                        <td class="head" align="center" width="70px">20+</td>
                        <td class="head" align="center" width="100px">Среднее</td>
                    </tr>
                </thead>
                <tbody>
<?php
$html = include 'timeman/table-1.php';
echo $html;
?>
                </tbody>
            </table>
        </div>



        <div id="tabs-2">
            <table class="grid" style="width: 900px; ">
                <thead height="50px">
                    <tr>
                        <td class="head" align="center"             >Оператор</td>
                        <td class="head" align="center" width="70px">0-15</td>
                        <td class="head" align="center" width="70px">15-30</td>
                        <td class="head" align="center" width="70px">30-45</td>
                        <td class="head" align="center" width="70px">45-60</td>
                        <td class="head" align="center" width="70px">60-120</td>
                        <td class="head" align="center" width="70px">120-180</td>
                        <td class="head" align="center" width="70px">180+</td>
                        <td class="head" align="center" width="100px">Среднее</td>
                    </tr>
                </thead>
                <tbody>
<?php
$html = include 'timeman/table-2.php';
echo $html;
?>
                </tbody>
            </table>
        </div>


        <div id="tabs-3">
            <table class="grid" style="width: 900px; ">
                <thead height="50px">
                    <tr>
                        <td class="head" align="center"             >Оператор</td>
                        <td class="head" align="center" width="70px">0-15</td>
                        <td class="head" align="center" width="70px">15-30</td>
                        <td class="head" align="center" width="70px">30-45</td>
                        <td class="head" align="center" width="70px">45-60</td>
                        <td class="head" align="center" width="70px">60-120</td>
                        <td class="head" align="center" width="70px">120-180</td>
                        <td class="head" align="center" width="70px">180+</td>
                        <td class="head" align="center" width="100px">Среднее</td>
                    </tr>
                </thead>
                <tbody>
<?php
$html = include 'timeman/table-3.php';
echo $html;
?>
                </tbody>
            </table>
        </div>



        <div id="tabs-4">
            <table class="grid" style="width: 900px; ">
                <thead height="50px">
                    <tr>
                        <td class="head" align="center"             >Оператор</td>
                        <td class="head" align="center" width="70px">0-1</td>
                        <td class="head" align="center" width="70px">1-5</td>
                        <td class="head" align="center" width="70px">5-10</td>
                        <td class="head" align="center" width="70px">10-15</td>
                        <td class="head" align="center" width="70px">15-20</td>
                        <td class="head" align="center" width="70px">20+</td>
                        <td class="head" align="center" width="100px">Среднее</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>



        <div id="tabs-5">
            <table class="grid" style="width: 900px; ">
                <thead height="50px">
                    <tr>
                        <td class="head" align="center"             >Оператор</td>
                        <td class="head" align="center" width="70px">0-1</td>
                        <td class="head" align="center" width="70px">1-5</td>
                        <td class="head" align="center" width="70px">5-10</td>
                        <td class="head" align="center" width="70px">10-15</td>
                        <td class="head" align="center" width="70px">15-20</td>
                        <td class="head" align="center" width="70px">20+</td>
                        <td class="head" align="center" width="100px">Среднее</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>




</div>