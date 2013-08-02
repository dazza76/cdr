<?php
/**
 * table-1.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
$html_out = "";

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
    $html_out .= "<td align=right>" . ($value['f0t15']?$value['f0t15']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f15t30']?$value['f15t30']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f30t45']?$value['f30t45']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f45t60']?$value['f45t60']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f60t120']?$value['f60t120']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f120t180']?$value['f120t180']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['f180tinf']?$value['f180tinf']:0) . "</td>";
    $html_out .= "<td align=right>" . ($value['average']?$value['average']:0) . "</td>";
    $html_out .= "</tr>";
};
return $html_out;
/****************************************/