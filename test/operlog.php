<?php
header('Content-type: text/html; charset=utf-8');
?>
<html>
<head>
<title>Статистика операторов</title>
</head>
<body>
<?
include 'header.php';
?>
<h1 align="center">Система анализа действий операторов</h1>
<h3 align="center">&copy;2010-2011</h3>
<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterisk';
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
//$sql_req = "SELECT * from agent_log WHERE 1;";
$sql_req = "	(SELECT datetime,agentid,action,agentphone,NULL,NULL FROM agent_log) UNION ALL (SELECT timestamp AS datetime,memberId AS agentid,'incoming',queue AS agentphone, callduration, ringtime FROM call_status) UNION ALL (SELECT calldate AS datetime,userfield AS agentid,'outcoming',src AS agentphone, duration, NULL FROM cdr WHERE length(src)<5) ORDER BY datetime;";
$result = mysql_query($sql_req,$db) or die(mysql_error());
?>
<form align="center" action="operlog.php" method="get">
<table align="center" border=1>
<tr>
<?
include 'calendar.php';
?>
<td align="center">
Оператор:
</td>
</select>
</td>
</tr>
<tr>
<td align="center">
<select name="oper">
<?
include 'users_list.php';
?>
</tr>
<tr>
<td align="center" valign="center" colspan=3> <input type=submit value="Поиск" /> </td> </tr>
</table>
</form>
<?php
$fromdate=$_GET["fromdate"]." ".$_GET["fromhour"].":".$_GET["frommin"];
$todate=$_GET["todate"]." ".$_GET["tohour"].":".$_GET["tomin"];
$html_out = '<table align="center" border="1">
			<thead height="50px">
			<tr height="50px" align="center">
			<td width="200px">Дата - Время</td>
			<td>Рабочее место</td>
			<td>Оператор</td>
			<td>Действие</td>
			</tr>
			</thead>';
while($row = mysql_fetch_array($result))
{
    if (
	((strtotime($row[0])>=strtotime($fromdate))||($fromdate==" 00:00")) && (($todate==" 00:00")||(strtotime($row[0])<=strtotime($todate))) && (($_GET["oper"]=="any")||($_GET["oper"]==$row[1]))
	)
    {
	    	$html_out .= '<tbody><tr>';
	    	$html_out .= '<td align="center">'.$row[0].'</td>';
		if(($row[3]<'3000') || ($row[3]>'10000'))
			$html_out .= '<td align=center>'.$row[3].'</td>';
		else
			$html_out .= '<td align=center>Оч. '.$row[3].'</td>';
                $html_out .= '<td>'.showoper($row[1]).'</td>';
		switch ($row[2])
		{
			case 'pausecall':
			$html_out .= '<td align=center>Поствызывная обработка</td>';
			if($pausecall_begin[$row[1]] == 0)
				$pausecall_begin[$row[1]] = strtotime($row[0]);
			break;
			case 'unpausecal':
			$html_out .= "<td align=center>Обработка завершена.</td><td>Время: ".(strtotime($row[0])-$pausecall_begin[$row[1]])." сек.</td>";
			$pausecall_length[$row[1]] += strtotime($row[0])-$pausecall_begin[$row[1]];
			$pausecall_begin[$row[1]]=0;
			break;
			case 'incoming':
			$html_out .= '<td align=center>Принят входящий ('.$row[3].')</td><td>Зв.: '.$row[5].' с/Разг.: '.$row[4].' с</td>';
			break;
			case 'outcoming':
			$html_out .= '<td align=center>Совершен исходящий</td><td>Длит.: '.$row[4].' с</td>';
			break;
			case 'ready':
			$html_out .= '<td align=center>Готов к работе</td>';
			break;
			case 'pause':
			$html_out .= '<td align=center>Ушел на перерыв</td>';
			if($pause_begin[$row[1]] == 0)
				$pause_begin[$row[1]] = strtotime($row[0]);
			break;
			case 'unpause':
			$html_out .= "<td align=\"center\">Вернулся с перерыва.</td><td>Время: ".(strtotime($row[0])-$pause_begin[$row[1]])." сек.</td>";
			$pause_length[$row[1]] += strtotime($row[0])-$pause_begin[$row[1]];
			$pause_begin[$row[1]]=0;
			break;
			case 'Login':
			$html_out .= '<td align="center">Вошел в очередь</td>';
			if($day_begin[$row[1]] == 0)
				$day_begin[$row[1]] = strtotime($row[0]);
			break;
			case 'Logout':
			$html_out .= '<td align="center">Вышел из очереди</td>';
			if ($day_begin != 0)
			{
				$day_length[$row[1]] = strtotime($row[0])-$day_begin[$row[1]];
				$day_begin[$row[1]]=0;
			};
			break;
			case 'Change':
			$html_out .= '<td align="center">Смена рабочего места</td>';
			break;
			case 'lost':
			$html_out .= '<td align="center">Потеря вызова</td>';
			break;
			case 'lostcall':
			$html_out .= '<td align="center">Потеря вызова</td>';
			break;
		}
	}
}
$html_out .='</table><br/>';
$html_out .= '<h2 align=center>Внимание! Данные доступны только по закрытым сменам!</h2><table align=center border=1><tr><td>Оператор</td><td>Длительность смены</td><td>Длительность перерывов</td><td>Длительность обработки</td><td>Суммарная наработка</td></tr><tr>';
for ($i=1000;$i<=2000;$i++)
{
	if ($day_length[$i] != 0)
	{
                $html_out .= '<td>'.showoper($i).'</td>';
		$work_length[$i] = $day_length[$i]-$pause_length[$i];
	        $html_out .= "<td align=center>".floor((int)$day_length[$i]/3600)."ч ".floor(((int)$day_length[$i]-(floor((int)$day_length[$i]/3600)*3600))/60)."мин</td>";
	        $html_out .= "<td align=center>".floor((int)$pause_length[$i]/3600)."ч ".floor(((int)$pause_length[$i]-(floor((int)$pause_length[$i]/3600)*3600))/60)."мин</td>";
	        $html_out .= "<td align=center>".floor((int)$pausecall_length[$i]/3600)."ч ".floor(((int)$pausecall_length[$i]-(floor((int)$pausecall_length[$i]/3600)*3600))/60)."мин</td>";
	        $html_out .= "<td align=center>".floor((int)$work_length[$i]/3600)."ч ".floor(((int)$work_length[$i]-(floor((int)$work_length[$i]/3600)*3600))/60)."мин</td>";
	};
	$html_out .= '</tr>';
};

echo $html_out;
//}
?>
</body>
</html>
