<?php
	function showoper($id)
	{
		// mysql_query("SET NAMES UTF8");
		global $db;
		// $res = mysql_query("SELECT name FROM queue_agents WHERE agentid = $id;");
		$res = $db->query("SELECT name FROM queue_agents WHERE agentid = $id;");

		if($res->num_rows)
			$row = $res->fetch_array();
		else
			return 'Неизвестно';
		return $row[0];
	}
	function form_switch($name,$value)
	{
		$tmparray = array("Вкл." => 'yes',"Выкл." => 'no');
		foreach($tmparray as $tmpkey => $tmpvalue)
		{
			$result .= "<INPUT type=\"radio\" name=\"".$name."\" value=\"".$tmpvalue."\" ";
			if($tmpvalue == $value)
				$result .= "checked ";
			$result .= "/>".$tmpkey."<br/>";
		}

		return $result;
	}
	function show_switch($value)
	{
		$tmparray = array('yes' => "Вкл.",'no' => "Выкл.");
		return $tmparray[$value];
	}




	$dbsrv = "127.0.0.1";
	$dbusr = "root";
	$dbpwd = "";
	// $dbcon = mysql_connect($dbsrv,$dbusr,$dbpwd);
	$db = new mysqli($dbsrv,$dbusr,$dbpwd, "asterix");
	// mysql_query("SET NAMES UTF8");
	$db->set_charset("UTF8");
	// mysql_select_db("asterix");
	// $goto = array('index.php' => "Главная",'settings.php' => "Настройки");
	// foreach($goto as $key => $value)
//	if($_SERVER['PHP_SELF'] == '/invalid_events/'.$key)
	//		echo('['.$value.']&nbsp;');
		//else
			// echo('<a href="'.$key.'">['.$value.']</a>&nbsp;');
?>