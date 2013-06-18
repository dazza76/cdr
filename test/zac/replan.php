<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterix';
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);

mysql_query("REPLACE INTO timeplan (agentid_month,totaltime) VALUES ('$_GET[cell]','$_GET[value]')") or die(mysql_error());
echo $location = "Location: ".$_SERVER['HTTP_REFERER'];
header("$location");
?>

