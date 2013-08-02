<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>
<?php
	$filter = '';
	include('header.php');
	include('filter.php');

	$html_out = "<TABLE align=\"center\" border=\"1\">";
	$html_out .= "<TR>";
	$html_out .= "<TD>Время регистрации</TD>";
	$html_out .= "<TD>Допустивший оператор</TD>";
	$html_out .= "<TD>Событие (пороговое значение)</TD>";
	$html_out .= "</TR>";

	$filter .= ' 1';

	echo $query = "SELECT * FROM invalid_events_notify WHERE ".$filter;
	$res = $db->query($query);
	while($row = $res->fetch_array())
	{
		$html_out .= "<TR><TD>".$row['dateofevent']."</TD><TD>".showoper($row['agentid'])."</TD><TD>".$row['reason']."</TD></TR>";
	}
	$html_out .= "</TABLE>";

	echo $html_out;
?>
<body>
</body>
</html>