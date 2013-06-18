<div style="width: 100% align: center valign: center border: 1px">
<?php
function showoper($id)
{
    global $db;
    
        mysql_query("SET NAMES UTF8", $db);
        $res = mysql_query("SELECT name FROM queue_agents WHERE agentid = '$id';", $db);
	if(mysql_num_rows($res))
	        $row = mysql_fetch_array($res);
	else
		return 'Неизвестно';
        return $row[0];
}
$param='?';
$param .= 'fromdate=';
if (isset($_GET["fromdate"]))
	$param .= $_GET["fromdate"];
$param .= '&fromhour=';
if (($_GET["fromhour"] == "") || (!(isset($_GET["fromhour"]))))
	$param .= "00";
else
	$param .= $_GET["fromhour"];
$param .= '&frommin=';
if (($_GET["frommin"] == "") || (!(isset($_GET["frommin"]))))
	$param .= "00";
else
	$param .= $_GET["frommin"];
$param .= '&todate=';
if (isset($_GET["todate"]))
	$param .= $_GET["todate"];
$param .= '&tohour=';
if (($_GET["tohour"] == "") || (!(isset($_GET["tohour"]))))
	$param .= "00";
else
	$param .= $_GET["tohour"];
$param .= '&tomin=';
if (($_GET["tomin"] == "") || (!(isset($_GET["tomin"]))))
	$param .= "00";
else
	$param .= $_GET["tomin"];
$param .= '&caller=';
if (isset($_GET["caller"]))
	$param .= $_GET["caller"];
$param .= '&oper=';
if (isset($_GET["oper"]))
	$param .= $_GET["oper"];
$param .= '&dialed=';
if (isset($_GET["dialed"]))
	$param .= $_GET["dialed"];
$param .= '&status=';
if (isset($_GET["status"]))
	$param .= $_GET["status"];
?>
<a href="cdr.php<?php echo $param;?>">Запись разговоров</a>
<!--<a href="cdr_outgoing.php<?php echo $param;?>">Запись исходящих</a>-->
<!--<a href="stat.php<?php echo $param;?>">Статистика</a>--!>
<a href="queue.php<?php echo $param;?>">Очередь</a>
<a href="queue_live.php<?php echo $param;?>">Текущая очередь</a>
<a href="load.php<?php echo $param;?>">Загрузка операторов</a>
<a href="operlog.php<?php echo $param;?>">Рабочее время операторов</a>
<a href="timeman.php<?php echo $param;?>">Длительности ожидания и разговоров</a>
<!-- <a href="analogue.php<?php echo $param;?>">Аналог</a>
<a href="autoinform.php<?php echo $param;?>">Автоинформатор</a>
<a href="callback.php<?php echo $param;?>">Callback</a>
<a href="newreport.php<?php echo $param;?>">Статистика по номерам</a>
<a href="newreport1.php<?php echo $param;?>">Статистика по номеру</a> --!>
<a href="outgoing.php<?php echo $param;?>">Исходящие</a>
</div>
