<?php
error_reporting(0);

header('Content-type: text/html; charset=utf-8');
$time = array('0','15','30','45','60','90','120','180','32768');
function formcallprofile($from,$to,$timemore,$timeless,$action)
{
        global $dbconn;
	global $queues;

	switch($action)
	{
		case 'complete':
		        $dbquery = "SELECT COUNT(callid) FROM call_status WHERE
		                        queue IN ($queues) AND
	              		        timestamp >= '$from' AND
        		                timestamp <= '$to' AND
                        		holdtime < '$timeless' AND
		                        holdtime >= '$timemore' AND
                		        status IN ('COMPLETECALLER','COMPLETEAGENT');";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">$dbqueryresultarray[0]</TD>\n";
		break;
		case 'abandoned':
		        $dbquery = "SELECT COUNT(callid) FROM call_status WHERE
                		        QUEUE IN ($queues) AND
		                        timestamp >= '$from' AND
                		        timestamp <= '$to' AND
		                        holdtime < '$timeless' AND
                		        holdtime >= '$timemore' AND
		                        status = 'ABANDON';";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">$dbqueryresultarray[0]</TD>\n";
		break;
		case 'transfered':
		        $dbquery = "SELECT COUNT(callid) FROM call_status WHERE
                		        QUEUE IN ($queues) AND
		                        timestamp >= '$from' AND
                		        timestamp <= '$to' AND
		                        holdtime < '$timeless' AND
                		        holdtime >= '$timemore' AND
		                        status = 'TRANSFER';";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">$dbqueryresultarray[0]</TD>\n";
		break;
		case 'avgcomplete':
		        $dbquery = "SELECT AVG(holdtime) FROM call_status WHERE
		                        QUEUE IN ($queues) AND
	              		        timestamp >= '$from' AND
        		                timestamp <= '$to' AND
                		        status IN ('COMPLETECALLER','COMPLETEAGENT');";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">".round($dbqueryresultarray[0])."</TD>\n";
		break;
		case 'avgabandoned':
		        $dbquery = "SELECT AVG(holdtime) FROM call_status WHERE
                		        QUEUE IN ($queues) AND
		                        timestamp >= '$from' AND
                		        timestamp <= '$to' AND
		                        status = 'ABANDON';";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">".round($dbqueryresultarray[0])."</TD>\n";
		break;
		case 'avgtransfered':
		        $dbquery = "SELECT AVG(holdtime) FROM call_status WHERE
                		        QUEUE IN ($queues) AND
		                        timestamp >= '$from' AND
                		        timestamp <= '$to' AND
		                        status = 'TRANSFER';";
		        $dbqueryresult = mysql_query($dbquery,$dbconn) or die(mysql_error());
		        echo $dbquery."<br><br><br>";
		        $dbqueryresultarray = mysql_fetch_array($dbqueryresult);
		        $result .= "<TD ALIGN=\"RIGHT\">".round($dbqueryresultarray[0])."</TD>\n";
		break;
	};

        return $result;
}
?>
<html>
<head>
<title>Статистика ожидания</title>
</head>
<body>
<?
include 'header.php';
?>
<h1 align="center">Система анализа работы операторов</h1>
<h3 align="center">&copy;2010-2011</h3>
<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_table = 'call_status';
$db_name = 'asterix';
$dbconn = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
$tempfromdate=substr($_GET["fromdate"],6,4)."-".substr($_GET["fromdate"],3,2)."-".substr($_GET["fromdate"],0,2)." ".$_GET["fromhour"].":".$_GET["frommin"];
$temptodate=substr($_GET["todate"],6,4)."-".substr($_GET["todate"],3,2)."-".substr($_GET["todate"],0,2)." ".$_GET["tohour"].":".$_GET["tomin"];
?>
<form align="center" action="timeman.php" method="get">
<table align="center" border=1>
<tr>
<?
include 'calendar.php';
?>
<td>
Очереди
</td>
</tr>
<tr>
<td>
<input type=checkbox name="queue3001" <?php if($_GET[queue3001]) echo checked;?>/> 3001
<br/>
<input type=checkbox name="queue3002" <?php if($_GET[queue3002]) echo checked;?>/> 3002
<br/>
<input type=checkbox name="queue3003" <?php if($_GET[queue3003]) echo checked;?>/> 3003
<br/>
<input type=checkbox name="queue3004" <?php if($_GET[queue3004]) echo checked;?>/> 3004
<br/>
<input type=checkbox name="queue3005" <?php if($_GET[queue3005]) echo checked;?>/> 3005
<br/>
<input type=checkbox name="queue3006" <?php if($_GET[queue3006]) echo checked;?>/> 3006
<br/>
<input type=checkbox name="queue3009" <?php if($_GET[queue3009]) echo checked;?>/> 3009
</td>
</tr>
<tr></tr>
<tr>
<td align="center" valign="center" colspan=3 rowspan=3> <input type=submit value="Поиск" /> </td> </tr>
</table>
</form>
<?php
$queues = "";
$fromdate=$_GET["fromdate"]." ".$_GET["fromhour"].":".$_GET["frommin"];
$todate=$_GET["todate"]." ".$_GET["tohour"].":".$_GET["tomin"];
if($_GET[queue3001])
	$queues .= ",'3001'";
if($_GET[queue3002])
	$queues .= ",'3002'";
if($_GET[queue3003])
	$queues .= ",'3003'";
if($_GET[queue3004])
	$queues .= ",'3004'";
if($_GET[queue3005])
	$queues .= ",'3005'";
if($_GET[queue3006])
	$queues .= ",'3006'";
if($_GET[queue3009])
	$queues .= ",'3009'";



$queues = substr($queues,1);/*
$html_out = '<table align="center" border="1">
			<thead height="50px">
			<td align=center width=100px>Время ожидания</td>';

foreach($time as $key=>$value)
	if(($key != count($time)) && ($key > '0'))
		$html_out .= "<td>".$time[$key-1]." - ".str_replace("32768","+",$value)."</td>";
$html_out .= '	<td align=center width=100px>Среднее</td>
		</thead>';
$html_out .= '<tr><td>Принято</td>';
foreach($time as $key=>$value)
	if(($key != count($time)) && ($key > '0'))
		$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'complete');

$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'avgcomplete');

$html_out .= '</tr><tr><td>Потеряно</td>';
foreach($time as $key=>$value)
	if(($key != count($time)) && ($key > '0'))
		$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'abandoned');

$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'avgabandoned');

$html_out .= '</tr><tr><td>Переведено</td>';
foreach($time as $key=>$value)
	if(($key != count($time)) && ($key > '0'))
		$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'transfered');

$html_out .= formcallprofile($tempfromdate,$temptodate,$time[$key-1],$value,'avgtransfered');

$html_out .='</tr></tbody></table><hr/>';
/**********************************************/









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
$res = mysql_query($dbquery);
echo $dbquery."<br><br><br>";
while($row = mysql_fetch_array($res))
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
$res = mysql_query($dbquery);
echo $dbquery."<br><br><br>";
$html_out .= '<table align="center" border="1">
			<thead height="50px">
			<td align=center width=100px>Поднятие трубки</td>
			<td align=center width=50px>0-3</td>
			<td align=center width=50px>3-7</td>
			<td align=center width=50px>7-10</td>
			<td align=center width=50px>10-20</td>
			<td align=center width=50px>20+</td>';
$html_out .= '	<td align=center width=100px>Среднее</td>
		</thead>';

while($row = mysql_fetch_array($res))
{
	$oper = $row['memberId'];
	foreach($row as $key => $value)
		if(in_array($key,array('f0t3','f3t7','f7t10','f10t20','f20tinf')))
			if($row[$key])
				$arr[$oper][$key] = $row['quantity'];
}
foreach($arr as $key => $value)
{
	$html_out .=  "<tr><td>".showoper($key)."</td>";
//	foreach($value as $key1 => $value1)
//		if($value1 == "")
//			$value[$key1] = 'TEST';

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







/*
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
$res = mysql_query($dbquery);
echo $dbquery."<br><br><br>";
while($row = mysql_fetch_array($res))
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
$html_out .= '<table align="center" border="1">
			<thead height="50px">
			<td align=center width=100px>Дительность входящих</td>
			<td align=center width=50px>0-15</td>
			<td align=center width=50px>15-30</td>
			<td align=center width=50px>30-45</td>
			<td align=center width=50px>45-60</td>
			<td align=center width=50px>60-120</td>
			<td align=center width=50px>120-180</td>
			<td align=center width=50px>180+</td>';
$html_out .= '	<td align=center width=100px>Среднее</td>
		</thead>';

$res = mysql_query($dbquery) or die(mysql_error());
echo $dbquery."<br><br><br>";
while($row = mysql_fetch_array($res))
{
	$oper = $row['memberId'];
	foreach($row as $key => $value)
		if(in_array($key,array('f0t15','f15t30','f30t35','f45t60','f60t120','f120t180','f180tinf')))
			if($row[$key])
				$arr[$oper][$key] = $row['quantity'];
}
foreach($arr as $key => $value)
{
	$html_out .=  "<tr><td>".showoper($key)."</td>";
//	foreach($value as $key1 => $value1)
//		if($value1 == "")
//			$value[$key1] = 'TEST';

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


/***
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
$res = mysql_query($dbquery);
echo $dbquery."<br><br><br>";
while($row = mysql_fetch_array($res))
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




$html_out .= '<table align="center" border="1">
			<thead height="50px">
			<td align=center width=100px>Дительность исходящих</td>
			<td align=center width=50px>0-15</td>
			<td align=center width=50px>15-30</td>
			<td align=center width=50px>30-45</td>
			<td align=center width=50px>45-60</td>
			<td align=center width=50px>60-120</td>
			<td align=center width=50px>120-180</td>
			<td align=center width=50px>180+</td>';
$html_out .= '	<td align=center width=100px>Среднее</td>
		</thead>';

$res = mysql_query($dbquery) or die(mysql_error());
echo $dbquery."<br><br><br>";
while($row = mysql_fetch_array($res))
{
	$oper = $row['memberId'];
	foreach($row as $key => $value)
		if(in_array($key,array('f0t15','f15t30','f30t35','f45t60','f60t120','f120t180','f180tinf')))
			if($row[$key])
				$arr[$oper][$key] = $row['quantity'];
}
foreach($arr as $key => $value)
{
	$html_out .=  "<tr><td>".showoper($key)."</td>";
//	foreach($value as $key1 => $value1)
//		if($value1 == "")
//			$value[$key1] = 'TEST';

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
</body>
</html>
