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
<?php
$tempfromdate = $this->fromdate->format('Y-m-d H:i').':00';
$temptodate = $this->todate->format('Y-m-d H:i').':00';

// $queues = @implode(',',$this->queue);
// if(!$queues) {
//     $queues =implode(',', array_keys(Queue::getQueueArr()));
// }

$queue_arr = $this->queue;
if(!$queue_arr) {
    $queue_arr = array_keys(Queue::getQueueArr());
}
foreach ($queue_arr as $queue) {
    $queues[]=App::Db()->quoteEscapeString($queue);
}
$queues = implode(',',$queues);
unset($queue_arr, $queue);
// -------------------------------------------


$dbquery = "SELECT
    memberId,
    AVG(ringtime) as average
FROM call_status WHERE
    queue IN ($queues) AND
    timestamp >= '".$tempfromdate."' AND
    timestamp <= '".$temptodate."' AND
    memberId <> 'NONE'
GROUP BY
    memberId
ORDER BY
    memberId ASC";
$res = App::Db()->query($dbquery);
// echo $dbquery."<br><br><br>";
while($row = @$res->fetch_array())
    $arr[$row['memberId']]['average'] = round($row['average'],1);
$dbquery = "SELECT
    memberId,
    (ringtime <= 3) AS f0t3,
    (ringtime <= 7 AND ringtime > 3) AS f3t7,
    (ringtime <= 10 AND ringtime > 7) AS f7t10,
    (ringtime <= 20 AND ringtime > 10) AS f10t20,
    (ringtime > 20) AS f20tinf,
    COUNT(ringtime) as quantity
FROM call_status WHERE
    queue IN ($queues) AND
    timestamp >= '".$tempfromdate."' AND
    timestamp <= '".$temptodate."' AND
    memberId <> 'NONE'
GROUP BY
    memberId,
    ringtime <= 3,
    ringtime <= 10,
    ringtime <= 30,
    ringtime > 30
ORDER BY
    memberId ASC,
    f0t3 DESC,
    f3t7 DESC,
    f7t10 DESC,
    f10t20 DESC,
    f20tinf DESC;";
$res = App::Db()->query($dbquery);
// echo $dbquery."<br><br><br>";
$html_out .= '<table class="grid" style="width: 800px; ">
        <thead height="50px">
            <td class="head" align=center >Поднятие трубки</td>
            <td class="head" align=center width=50px>0-3</td>
            <td class="head" align=center width=50px>3-7</td>
            <td class="head" align=center width=50px>7-10</td>
            <td class="head" align=center width=50px>10-20</td>
            <td class="head" align=center width=50px>20+</td>';
$html_out .= '  <td class="head" align=center width=100px>Среднее</td>
        </thead>';

while($row = @$res->fetch_array())
{
    $oper = $row['memberId'];
    foreach($row as $key => $value)
        if(in_array($key,array('f0t3','f3t7','f7t10','f10t20','f20tinf')))
            if($row[$key])
                $arr[$oper][$key] = $row['quantity'];
}
if(!is_array($arr) ) {
    $arr = array();
}
foreach($arr as $key => $value)
{
    $html_out .=  "<tr><td>".QueueAgent::getOper($key)."</td>";
//  foreach($value as $key1 => $value1)
//      if($value1 == "")
//          $value[$key1] = 'TEST';

    $html_out .= "<td align=right>";
    $html_out .= $value['f0t3']?$value['f0t3']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f3t7']?$value['f3t7']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f7t10']?$value['f7t10']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f10t20']?$value['f10t20']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f20tinf']?$value['f20tinf']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['average']?$value['average']:0;
    $html_out .= "</td>";
    $html_out .= "</tr>";
};
$html_out .='</tr></tbody></table><hr/>';
/**********************************************/







/* ************************** */
$dbquery = "SELECT
    memberId,
    AVG(callduration) as average
FROM call_status WHERE
    queue IN ($queues) AND
    timestamp >= '".$tempfromdate."' AND
    timestamp <= '".$temptodate."' AND
    memberId <> 'NONE'
GROUP BY
    memberId
ORDER BY
    memberId ASC";
$res = App::Db()->query($dbquery);
// echo $dbquery."<br><br><br>";
while($row = @$res->fetch_array())
    $arr[$row['memberId']]['average'] = round($row['average'],1);

$dbquery = "SELECT
    memberId,
    (callduration <= 15) AS f0t15,
    (callduration <= 30 AND callduration > 15) AS f15t30,
    (callduration <= 45 AND callduration > 30) AS f30t45,
    (callduration <= 60 AND callduration > 45) AS f45t60,
    (callduration <= 120 AND callduration > 60) AS f60t120,
    (callduration <= 180 AND callduration > 120) AS f120t180,
    (callduration > 180) AS f180tinf,
    COUNT(callduration) as quantity
FROM call_status WHERE
    queue IN ($queues) AND
    timestamp >= '".$tempfromdate."' AND
    timestamp <= '".$temptodate."' AND
    memberId <> 'NONE'
GROUP BY
    memberId,
    callduration <= 15,
    callduration <= 30,
    callduration <= 45,
    callduration <= 60,
    callduration <= 120,
    callduration <= 180,
    callduration > 180
ORDER BY
    memberId ASC,
    f0t15 DESC,
    f15t30 DESC,
    f30t45 DESC,
    f45t60 DESC,
    f60t120 DESC,
    f120t180 DESC,
    f180tinf DESC;";
$html_out .= '<table class="grid" style="width: 800px; ">
            <thead height="50px">
            <td class="head" align=center width=100px>Длительность входящих</td>
            <td class="head" align=center width=50px>0-15</td>
            <td class="head" align=center width=50px>15-30</td>
            <td class="head" align=center width=50px>30-45</td>
            <td class="head" align=center width=50px>45-60</td>
            <td class="head" align=center width=50px>60-120</td>
            <td class="head" align=center width=50px>120-180</td>
            <td class="head" align=center width=50px>180+</td>';
$html_out .= '  <td class="head" align=center width=100px>Среднее</td>
        </thead>';

$res = App::Db()->query($dbquery) or die(mysql_error());
// echo $dbquery."<br><br><br>";
while($row = @$res->fetch_array())
{
    $oper = $row['memberId'];
    foreach($row as $key => $value)
        if(in_array($key,array('f0t15','f15t30','f30t35','f45t60','f60t120','f120t180','f180tinf')))
            if($row[$key])
                $arr[$oper][$key] = $row['quantity'];
}
foreach($arr as $key => $value)
{
    $html_out .=  "<tr><td>".QueueAgent::getOper($key)."</td>";
//  foreach($value as $key1 => $value1)
//      if($value1 == "")
//          $value[$key1] = 'TEST';

    $html_out .= "<td align=right>";
    $html_out .= $value['f0t15']?$value['f0t15']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f15t30']?$value['f15t30']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f30t45']?$value['f30t45']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f45t60']?$value['f45t60']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f60t120']?$value['f60t120']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f120t180']?$value['f120t180']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f180tinf']?$value['f180tinf']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['average']?$value['average']:0;
    $html_out .= "</td>";
    $html_out .= "</tr>";
};
$html_out .='</tr></tbody></table><hr/>';
/**********************************************/


/***/
$dbquery = "SELECT
    userfield as memberId,
    AVG(duration) as average
FROM cdr WHERE
    calldate >= '".$tempfromdate."' AND
    calldate <= '".$temptodate."' AND
    dcontext IN ('world','country','city','local') AND
    LENGTH(dst) >= 8 AND
    LENGTH(userfield) > 0
GROUP BY
    memberId
ORDER BY
    memberId ASC";
$res = App::Db()->query($dbquery);
// echo $dbquery."<br><br><br>";
while($row = @$res->fetch_array())
    $arr[$row['memberId']]['average'] = round($row['average'],1);

$dbquery = "SELECT
    userfield as memberId,
    (duration <= 15) AS f0t15,
    (duration <= 30 AND duration > 15) AS f15t30,
    (duration <= 45 AND duration > 30) AS f30t45,
    (duration <= 60 AND duration > 45) AS f45t60,
    (duration <= 120 AND duration > 60) AS f60t120,
    (duration <= 180 AND duration > 120) AS f120t180,
    (duration > 180) AS f180tinf,
    COUNT(duration) as quantity
FROM cdr WHERE
    calldate >= '".$tempfromdate."' AND
    calldate <= '".$temptodate."' AND
    dcontext IN ('world','country','city','local') AND
    LENGTH(dst) >= 8 AND
    LENGTH(userfield) > 0
GROUP BY
    memberId,
    duration <= 15,
    duration <= 30,
    duration <= 45,
    duration <= 60,
    duration <= 120,
    duration <= 180,
    duration > 180
ORDER BY
    memberId ASC,
    f0t15 DESC,
    f15t30 DESC,
    f30t45 DESC,
    f45t60 DESC,
    f60t120 DESC,
    f120t180 DESC,
    f180tinf DESC;";




$html_out .= '<table class="grid" style="width: 800px; ">
            <thead height="50px">
            <td class="head" align=center width=100px>Длительность исходящих</td>
            <td class="head" align=center width=50px>0-15</td>
            <td class="head" align=center width=50px>15-30</td>
            <td class="head" align=center width=50px>30-45</td>
            <td class="head" align=center width=50px>45-60</td>
            <td class="head" align=center width=50px>60-120</td>
            <td class="head" align=center width=50px>120-180</td>
            <td class="head" align=center width=50px>180+</td>';
$html_out .= '  <td class="head" align=center width=100px>Среднее</td>
        </thead>';

$res = App::Db()->query($dbquery) or die(mysql_error());
// echo $dbquery."<br><br><br>";
while($row =@$res->fetch_array($res))
{
    $oper = $row['memberId'];
    foreach($row as $key => $value)
        if(in_array($key,array('f0t15','f15t30','f30t35','f45t60','f60t120','f120t180','f180tinf')))
            if($row[$key])
                $arr[$oper][$key] = $row['quantity'];
}
foreach($arr as $key => $value)
{
    $html_out .=  "<tr><td>".QueueAgent::getOper($key)."</td>";
//  foreach($value as $key1 => $value1)
//      if($value1 == "")
//          $value[$key1] = 'TEST';

    $html_out .= "<td align=right>";
    $html_out .= $value['f0t15']?$value['f0t15']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f15t30']?$value['f15t30']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f30t45']?$value['f30t45']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f45t60']?$value['f45t60']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f60t120']?$value['f60t120']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f120t180']?$value['f120t180']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['f180tinf']?$value['f180tinf']:0;
    $html_out .= "</td>";
    $html_out .= "<td align=right>";
    $html_out .= $value['average']?$value['average']:0;
    $html_out .= "</td>";
    $html_out .= "</tr>";
};
$html_out .='</tr></tbody></table><hr/>';
/****************************************/


echo $html_out;

?>

</div>