<?php
include 'header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>
<?php



	$html_out = "<p align=\"right\"><a href=\"settings.php?act=add\">Добавить</a></p>";
	$html_out .= "<TABLE align=\"center\" border=\"1\">";
	$html_out .= "<TR>";
	$html_out .= "<TD>Причина срабатывания</TD>";
	$html_out .= "<TD>Имя модуля</TD>";
	$html_out .= "<TD>Значение</TD>";
	$html_out .= "<TD>Регистрация<BR/>события</TD>";
	$html_out .= "<TD>Немедленно<br />уведомлять</TD>";
	$html_out .= "<TD>Функции</TD>";
	$html_out .= "</TR>";
	switch($_GET['act'])
	{
		case 'save':
			$query = "REPLACE INTO invalid_events_modules (id,name,filename,value,enabled,urgent) VALUES ('".$_GET['id']."','".$_GET['name']."','".$_GET['filename']."','".$_GET['value']."','".$_GET['enabled']."','".$_GET['urgent']."')";
			$db->query($query);
			header('Location: settings.php');
		break;
		case 'edit':
			$res = $db->query("SELECT * FROM invalid_events_modules WHERE id = '".$_GET['id']."'");
			$row = $res->fetch_array();
			$html_out .= "<FORM action=\"settings.php\"><input type=\"hidden\" name=\"act\" value=\"save\"/><input type=\"hidden\" name=\"id\" value=\"".$row['id']."\"/>";
			$html_out .= "<TR>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"45\" name=\"name\" value=\"".$row['name']."\"/></TD>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"45\" name=\"filename\" value=\"".$row['filename']."\"/></TD>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"5\" name=\"value\" value=\"".$row['value']."\"/></TD>";
			$html_out .= "<TD>".form_switch('enabled',$row['enabled'])."</TD>";
			$html_out .= "<TD>".form_switch('urgent',$row['urgent'])."</TD>";
			$html_out .= "<TD><INPUT type=\"submit\" VALUE=\"Сохранить\" /></TD>";
			$html_out .= "</TR>";
			$html_out .= "</FORM>";
		break;
		case 'add':
			$res = $db->query("SHOW TABLE STATUS LIKE 'invalid_events_modules'");
			$row = $res->fetch_array();
			$row = $row['Auto_increment'];
			echo $row;
			$html_out .= "<FORM action=\"settings.php\"><input type=\"hidden\" name=\"act\" value=\"save\"/><input type=\"hidden\" name=\"id\" value=\"".$row."\"/>";
			$html_out .= "<TR>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"45\" name=\"name\"/></TD>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"45\" name=\"filename\"/></TD>";
			$html_out .= "<TD><INPUT type=\"text\" size=\"5\" name=\"value\"/></TD>";
			$html_out .= "<TD>".form_switch('enabled','yes')."</TD>";
			$html_out .= "<TD>".form_switch('urgent','no')."</TD>";
			$html_out .= "<TD><INPUT type=\"submit\" VALUE=\"Сохранить\" /></TD>";
			$html_out .= "</TR>";
			$html_out .= "</FORM>";
		break;
		case 'delete':
			$db->query("DELETE FROM invalid_events_modules WHERE id='".$_GET['id']."';");
			header('Location: settings.php');
		break;
		default:
			$res = $db->query("SELECT * FROM invalid_events_modules WHERE 1");
			while($row = $res->fetch_array())
			{
				$html_out .= "<TR>";
				$html_out .= "<TD>".$row['name']."</TD>";
				$html_out .= "<TD>".$row['filename']."</TD>";
				$html_out .= "<TD>".$row['value']."</TD>";
				$html_out .= "<TD>".show_switch($row['enabled'])."</TD>";
				$html_out .= "<TD>".show_switch($row['urgent'])."</TD>";
				$html_out .= "<TD><a href=\"settings.php?act=edit&id=".$row['id']."\">Изменить</a><br/><a href=\"settings.php?act=delete&id=".$row['id']."\">Удалить</a></TD>";
				$html_out .= "</TR>";
			}
		break;

	};
	$html_out .= "</TABLE>";

	echo $html_out;
?>
<body>
</body>
</html>