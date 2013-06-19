<?php
$script_start = microtime(true);
$maxring = '10';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterix';

$month = array(
	"01"=>"Январь",
	"02"=>"Февраль",
	"03"=>"Март",
	"04"=>"Апрель",
	"05"=>"Май",
	"06"=>"Июнь",
	"07"=>"Июль",
	"08"=>"Август",
	"09"=>"Сентябрь",
	"10"=>"Октябрь",
	"11"=>"Ноябрь",
	"12"=>"Декабрь"
);

$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);

include('header.php');
$output = "<style>tr.data:hover {background:#FACE8D; } table {border: 0px; } td { border: solid 1px; }</style>";
$output .= "<h1 align=center>Месячный отчет</h1><form >";
$output .= "<table border=\"1\" align=center>";
$output .= "<tr><td align=center>Месяц</td><td align=center>Год</td><td align=center>Оператор</td></tr>";
$output .= "<tr><td align=center>";
$output .= "<select name=month>";
foreach($month as $key=>$value)
{
	$output .= "<option value=$key";
	if($_GET[month]==$key)
		$output .= " selected";
	$output .= ">$value</option>";
}
$output .= "</select>";
$output .= "</td>";
$output .= "<td align=center>";
$output .= "<select name=year>";
$res = mysql_query("SELECT DISTINCT SUBSTR(timestamp,1,4) FROM call_status") or die(mysql_error());
while($row = mysql_fetch_array($res))
{
	$output .= "<option value=$row[0]";
	if($_GET[year]==$row[0])
		$output .= " selected";
	$output .= ">$row[0]</option>";
}
$output .= "</select>";
$output .= "</td>";
$output .= "<td align=center>";
$output .= "<select name=oper>";
$res = mysql_query("SELECT DISTINCT agentid FROM queue_agents ORDER BY name") or die(mysql_error());
$output .= "<option value=any";
	if($_GET[oper]=="any")
		$output .= " selected";
	$output .= ">Любой</option>";
while($row = mysql_fetch_array($res))
{
	$output .= "<option value=$row[0]";
	if($_GET[oper]==$row[0])
		$output .= " selected";
	$output .= ">".showoper($row[0])."</option>";
}
$output .= "</select>";
$output .= "</td></tr>";
$output .= "<tr><td colspan=3 align=center><input type=submit value=Поиск /></td></tr>";
$output .= "</table></form>";

if(!$_GET)
{
	$output .= "<h1 align=center>Введите параметры.</h1>";
}
else
{
	$output .= "<table align=center border=1>";
	$output .= "<tr>";
	$output .= "<td align=center>Оператор</td>";
	$output .= "<td align=center>Входящие<br/>шт.</td>";
	$output .= "<td align=center>Исходящие<br/>шт.</td>";
	$output .= "<td align=center>Всего вызовов<br/>шт.</td>";
	$output .= "<td align=center>Простой<br/>ЧЧ:ММ:СС</td>";
	$output .= "<td align=center>Обработка<br/>ЧЧ:ММ:СС</td>";
	$output .= "<td align=center>Перерыв<br/>ЧЧ:ММ:СС</td>";
	$output .= "<td align=center>Долгое<br/>поднятие<br/>трубки, шт.</td>";
	$output .= "<td align=center>Ср. вр. разговора<br/>сек.</td>";
	$output .= "<td align=center>Ср. вр. подн. трубки<br/>сек.</td>";
	$output .= "</tr>";
	$from = "$_GET[year]-$_GET[month]-01";
	$to = date('Y-m-d',strtotime(date('Y-m-t',strtotime($from)))+86400);
	if($_GET[oper] != "any")
		$query = "SELECT DISTINCT memberId FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$_GET[oper]';";
	else
		$query = "SELECT DISTINCT memberId FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId < 2000 AND memberId <> 'NONE';";
	
        $res = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($res))
	{
		$output .= "<tr class=data>";
		$output .= "<td align=left>".showoper($row[0])."</td>";

 		$tempquery = "SELECT COUNT(callId) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]';";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		$output .= "<td align=right>".$temprow[0]."</td>";
		$sum = $temprow[0];

 		$tempquery = "SELECT COUNT(uniqueid) FROM cdr WHERE calldate >= '$from' AND calldate < '$to' AND userfield = '$row[0]';";
		$tempres = mysql_query($tempquery) or die(mysql_error());
		$temprow = mysql_fetch_array($tempres);
		$output .= "<td align=right>".$temprow[0]."</td>";
		$sum += $temprow[0];

		$output .= "<td align=right>".$sum."</td>";

                // 1 ----
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('Login', 'Logout') ORDER BY datetime ASC;";
		$tempres = mysql_query($tempquery);
		$actions = mysql_num_rows($tempres);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'Login':
				$start = strtotime($temprow[0]);
			break;
			case 'Logout':
				$start = strtotime($from);
			break;
		}
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('Login', 'Logout') ORDER BY datetime DESC LIMIT 1;";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'Logout':
				$end = strtotime($temprow[0]);
			break;
			case 'Login':
				$end = strtotime($to);
			break;
		}
		$total_time = $end - $start;
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime > '".date('Y-m-d H:i:s',$start)."' AND datetime < '".date('Y-m-d H:i:s',$end)."' AND agentid = '$row[0]' AND action IN ('Login', 'Logout');";
		$tempres = mysql_query($tempquery);
		$teststring = "";
		while($temprow = mysql_fetch_array($tempres))
		{
			$teststring .= $temprow[1];
			switch($temprow[1])
			{
				case 'Login':
					$total_time -= strtotime($temprow[0]);
				break;
				case 'Logout':
					$total_time += strtotime($temprow[0]);
				break;
			}
		}
		$hours = (int)($total_time / 3600);
		$minutes = (int)(($total_time-$hours*3600) / 60);
		if($minutes < 10)
			$minutes = "0$minutes";
		$seconds = ($total_time-$hours*3600-$minutes*60) % 60;
		if($seconds < 10)
			$seconds = "0$seconds";
		$total_time = "$hours:$minutes:$seconds";
		$output .= "<td align=right>$total_time(".substr_count($teststring,"LoginLogin")."/".substr_count($teststring,"LogoutLogout").")</td>";
                // -------------------------------------------------------------
                // -------------------------------------------------------------
                // -------------------------------------------------------------
                
                // 2---
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('pause', 'unpause') ORDER BY datetime ASC;";
		$tempres = mysql_query($tempquery);
		$actions = mysql_num_rows($tempres);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'pause':
				$start = strtotime($temprow[0]);
			break;
			case 'unpause':
				$start = strtotime($from);
			break;
		}
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('pause', 'unpause') ORDER BY datetime DESC LIMIT 1;";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'unpause':
				$end = strtotime($temprow[0]);
			break;
			case 'pause':
				$end = strtotime($to);
			break;
		}
		$total_time = $end - $start;
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime > '".date('Y-m-d H:i:s',$start)."' AND datetime < '".date('Y-m-d H:i:s',$end)."' AND agentid = '$row[0]' AND action IN ('pause', 'unpause');";
		$tempres = mysql_query($tempquery);
		$teststring = "";
		while($temprow = mysql_fetch_array($tempres))
		{
			$teststring .= $temprow[1];
			switch($temprow[1])
			{
				case 'pause':
					$total_time -= strtotime($temprow[0]);
				break;
				case 'unpause':
					$total_time += strtotime($temprow[0]);
				break;
			}
		}
		$hours = (int)($total_time / 3600);
		$minutes = (int)(($total_time-$hours*3600) / 60);
		if($minutes < 10)
			$minutes = "0$minutes";
		$seconds = ($total_time-$hours*3600-$minutes*60) % 60;
		if($seconds < 10)
			$seconds = "0$seconds";
		$total_time = "$hours:$minutes:$seconds";
		$output .= "<td align=right>$total_time(".substr_count($teststring,"pausepause")."/".substr_count($teststring,"unpauseunpause").")</td>";
                // -------------------------------------------------------------
                // -------------------------------------------------------------
                // -------------------------------------------------------------
                
                // 3--
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('pausecall', 'unpausecal') ORDER BY datetime ASC;";
		$tempres = mysql_query($tempquery);
		$actions = mysql_num_rows($tempres);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'pausecall':
				$start = strtotime($temprow[0]);
			break;
			case 'unpausecal':
				$start = strtotime($from);
			break;
		}
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' AND action IN ('pausecall', 'unpausecal') ORDER BY datetime DESC LIMIT 1;";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		switch($temprow[1])
		{
			case 'unpausecal':
				$end = strtotime($temprow[0]);
			break;
			case 'pausecall':
				$end = strtotime($to);
			break;
		}
		$total_time = $end - $start;
		$tempquery = "SELECT datetime,action FROM agent_log WHERE datetime > '".date('Y-m-d H:i:s',$start)."' AND datetime < '".date('Y-m-d H:i:s',$end)."' AND agentid = '$row[0]' AND action IN ('pausecall', 'unpausecal');";
		$tempres = mysql_query($tempquery);
		$teststring = "";
		while($temprow = mysql_fetch_array($tempres))
		{
			$teststring .= $temprow[1];
			switch($temprow[1])
			{
				case 'pausecall':
					$total_time -= strtotime($temprow[0]);
				break;
				case 'unpausecal':
					$total_time += strtotime($temprow[0]);
				break;
			}
		}
		$hours = (int)($total_time / 3600);
		$minutes = (int)(($total_time-$hours*3600) / 60);
		if($minutes < 10)
			$minutes = "0$minutes";
		$seconds = ($total_time-$hours*3600-$minutes*60) % 60;
		if($seconds < 10)
			$seconds = "0$seconds";
		$total_time = "$hours:$minutes:$seconds";
		$output .= "<td align=right>$total_time(".substr_count($teststring,"pausecallpausecall")."/".substr_count($teststring,"unpausecalunpausecal").")</td>";
                // -------------------------------------------------------------
                // -------------------------------------------------------------
                // -------------------------------------------------------------

 		$tempquery = "SELECT COUNT(callId) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]' AND ringtime > '$maxring';";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		$output .= "<td align=right>".$temprow[0]."</td>";

 		$tempquery = "SELECT AVG(callduration),AVG(ringtime) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]';";
		$tempres = mysql_query($tempquery);
		$temprow = mysql_fetch_array($tempres);
		$output .= "<td align=right>".round($temprow[0])."</td>";
		$output .= "<td align=right>".round($temprow[1],1)."</td>";
	}
	$output .= "</table>";
	$output .= "<h5 align=center>Время подготовки отчета: ".round(microtime(true) - $script_start,4)." сек.</h5></body></html>";
}

echo $output;
?>
