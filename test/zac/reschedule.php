<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterix';
	$db = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db($db_name);

	$date = explode("-",$_GET[view]);
	$data = explode(":",$_GET[data]);
	foreach($data as $key => $value)
		echo "$key => $value<br/>";
	switch($data[2])
	{
		case 'off':
			for($i = 0; $i < $data[3]; $i++)
				mysql_query("DELETE FROM timetable WHERE agentid_day='$data[1].".date('Y-m-d',(strtotime($data[0]) + 86400*$i))."'") or die(mysql_error());
		break;
		case 'vac':
			for($i = 0; $i < $data[3]; $i++)
				mysql_query("REPLACE INTO timetable (agentid_day,event) VALUES ('$data[1].".date('Y-m-d',(strtotime($data[0]) + 86400*$i))."','vac')") or die(mysql_error());
		break;
		case 'ill':
			for($i = 0; $i < $data[3]; $i++)
				mysql_query("REPLACE INTO timetable (agentid_day,event) VALUES ('$data[1].".date('Y-m-d',(strtotime($data[0]) + 86400*$i))."','ill')") or die(mysql_error());
		break;
		case 'job':
			for($i = 0; $i < $data[6]; $i++)
				mysql_query("REPLACE INTO timetable (agentid_day,event,start,duration) VALUES ('$data[1].".date('Y-m-d',(strtotime($data[0]) + 86400*$i))."','job','$data[3]:$data[4]','$data[5]');") or die(mysql_error());
		break;
	}

	echo $location = 'Location: '.$_SERVER[HTTP_REFERER];
	header($location);
?>

