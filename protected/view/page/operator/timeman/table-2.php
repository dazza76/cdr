<?php
/**
 * table-1.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
$html_out = "";


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
    $html_out .=  "<tr>";
    $html_out .=  "<td>".QueueAgent::getOper($key)."</td>";
    $html_out .= "<td align=right>" . ($value['f0t15']?$value['f0t15']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f15t30']?$value['f15t30']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f30t45']?$value['f30t45']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f45t60']?$value['f45t60']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f60t120']?$value['f60t120']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f120t180']?$value['f120t180']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f180tinf']?$value['f180tinf']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['average']?$value['average']:0) . "</td>";
    $html_out .= "</tr>";
}
return $html_out;
/**********************************************/
