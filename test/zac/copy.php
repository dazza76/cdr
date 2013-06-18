<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterix';
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
$date = explode('-',$_GET[date]);

foreach($_GET as $key => $value)
	if(!is_numeric($key))
		echo "$key=>$value</br>";

mysql_query("DELETE FROM timetable WHERE agentid_day LIKE '$_GET[where].$_GET[date]-%';");
$res = mysql_query("SELECT SUBSTR(agentid_day,6) as agentid_day, event, start, duration FROM timetable WHERE agentid_day LIKE '$_GET[from].$_GET[date]-%';") or die(mysql_error());
while($row = mysql_fetch_array($res))
{
	$keys = '';
	$values = '';
	foreach($row as $key => $value)
		if(!is_numeric($key))
		{
			$keys .= ",$key";
			$values .= ",'$value'";
		};
	$keys = substr($keys,1);
	$values = "'".$_GET[where].".".substr($values,2);
	mysql_query("REPLACE INTO timetable ($keys) VALUES ($values);");

}
echo $location = 'Location: '.$_SERVER[HTTP_REFERER];
header($location);

?>

