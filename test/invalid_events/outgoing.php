<?php
header('Content-type: text/html; charset=utf-8');
include 'header.php';
?>
<html>
<head>
<?php
//<style type="text/css">
//</style>
?>
<title>Запись переговоров</title>
</head>
<body>
<h1 align="center">Детализация исходящих вызовов</h1>
<h3 align="center">&copy;2010-2011</h3>

<?php
//var_dump(file_exists($_SERVER['DOCUMENT_ROOT'].$rec_directory.$row[uniqueid].$rec_ext));

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_table = 'cdr';
$db_name = 'asterix';
$rec_directory = '/srv/www/monitor/';
$rec_ext = '.wav';

//var_dump(file_exists($_SERVER['DOCUMENT_ROOT'].$rec_directory."1291376314.1781".$rec_ext));

//echo $db;
//file_exist('/1291294750.54.wav');
//$ifex = file_exist('./index.php');
//echo $ifex;
//$ldir = dir('.');
///echo $ldir;
//function drawCDRtable()
//{
//echo $result;
?>

<form align="center" action="outgoing.php" method="get">
<table align="center" border=1>
<tr align="center">
<?php
	// include 'calendar.php';
?>
<td align="center">
Источник
</td>
<td align="center">
Статус
</td>
<td align="center">
Назначение
</td>
</tr>
<tr align="center">
<td align="center" width="150 px">
<input type=text name="caller" maxlength=10 />
</td>
<td align="center" width="150 px">
<select name="disposition">
<option value="any">Любой</option>
<option value="BUSY">Занято</option>
<option value="NO ANSWER">Нет ответа</option>
<option value="ANSWERED">Принят</option>
</select>
</td>
<td align="center" width="150 px">
<input name="dialed" type="text" maxlength=10>
</td>
</tr>
<tr>
<td colspan=4 align="center" valign="center">
<input type=submit value="Поиск" />
</td>
</tr>
</table>
</form>
<?php
$fromdate=$_GET["fromdate"]." ".$_GET["fromhour"].":".$_GET["frommin"];
$todate=$_GET["todate"]." ".$_GET["tohour"].":".$_GET["tomin"];
$tempfromdate=substr($_GET["fromdate"],6,4)."-".substr($_GET["fromdate"],3,2)."-".substr($_GET["fromdate"],0,2)." ".$_GET["fromhour"].":".$_GET["frommin"];
$temptodate=substr($_GET["todate"],6,4)."-".substr($_GET["todate"],3,2)."-".substr($_GET["todate"],0,2)." ".$_GET["tohour"].":".$_GET["tomin"];
$tempto = "";
$tempfrom = "";
$temp = "channel NOT LIKE '%Local%' AND dcontext IN ('city','world','country')";
//$temp = "1";
if(($fromdate != ' 00:00')&&($fromdate != ' :'))
{
	$temp .= " AND calldate >= '$tempfromdate'";
};
if(($todate != ' 00:00')&&($todate != ' :'))
{
	$temp .= " AND calldate <= '$temptodate'";
};
if($_GET["dialed"] != "")
{
	$temp .= " AND dst LIKE '%$_GET[dialed]%'";
};
if($_GET["caller"] != "")
{
	$temp .= " AND src LIKE '%$_GET[caller]%'";
};
if(isset($_GET['disposition']) && $_GET["disposition"] != "any")
{
	$temp .= " AND disposition = '$_GET[disposition]'";
};
echo $sql_req = "SELECT * from cdr WHERE $temp AND LENGTH(src) < 5  AND ((LENGTH(dst) > 7) OR (dst LIKE '#%')) ORDER BY calldate;";
$result = $db->query($sql_req) or die ('query');
$html_out = '<table align="center" border="1">
			<thead>
			<tr>
			<td>Дата - Время</td>
			<td>Источник</td>
			<td>Назначение</td>
			<td>Ожидание</td>
			<td>Разговор</td>
			<td>Результат</td>
			</tr>
			</thead>';
while($row = $result->fetch_array())
{
	$html_out .='<tbody><tr>';
	$count = count($row);
	$html_out .= '<td>'.$row[calldate].'</td>';
	$html_out .= '<td>'.showoper($row[userfield]).'('.$row[src].')</td>';
	if(strlen($row[dst])>4)
	    	$html_out .= '<td>'.substr($row[dst],1).'</td>';
    	else
		$html_out .= '<td>'.$row[dst].'</td>';
	$html_out .= '<td>'.($row[duration]-$row[billsec]).'</td>';
	$html_out .= '<td>'.$row[billsec].'</td>';
	switch($row[disposition])
	{
		case 'BUSY':
			$html_out .= '<td>Занято</td>';
		break;
		case 'ANSWERED':
			$html_out .= '<td>Принят</td>';
		break;
		case 'NO ANSWER':
			$html_out .= '<td>Нет ответа</td>';
		break;
		default:
			$html_out .= '<td>Н/Д</td>';
		break;
	}
	$html_out .='</tr></tbody>';
}
$html_out .='</table>';

echo $html_out;
//}
?>
</body>
</html>

