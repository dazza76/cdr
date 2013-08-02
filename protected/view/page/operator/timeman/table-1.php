<?php
/**
 * table-1.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
$html_out = "";

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
    $html_out .=  "<tr>";
    $html_out .= "<td>" . QueueAgent::getOper($key) . "</td>";
    $html_out .= "<td align=right>". ($value['f0t3']?$value['f0t3']:0) . "</td>";
    $html_out .= "<td align=right>". ($value['f3t7']?$value['f3t7']:0) . "</td>";
    $html_out .= "<td align=right>". ($value['f7t10']?$value['f7t10']:0) . "</td>";
    $html_out .= "<td align=right>". ($value['f10t20']?$value['f10t20']:0) . "</td>";
    $html_out .= "<td align=right>". ($value['f20tinf']?$value['f20tinf']:0) . "</td>";
    $html_out .= "<td align=right>". ($value['average']?$value['average']:0) . "</td>";
    $html_out .= "</tr>\n";
}

return $html_out ;
/**********************************************/