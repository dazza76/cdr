<?php
	function form_oper_list($name)
	{
		global $oper_list, $db;
		$tmp = $db->query("SELECT name,agentid FROM queue_agents") or die(mysql_error());
		while($tmprow = $tmp->fetch_array())
		{
			$result .= "<input type=\"checkbox\" name=\"".$name."_".$tmprow['agentid']."\"";
			if($_GET[$name.'_'.$tmprow['agentid']] == 'on')
				$result .= "checked";
			$result .= ">".$tmprow['name']."<br/>";
		}
		return $result;
	};

	foreach($_GET as $key => $value)
		if(preg_match("/^oper_/",$key))
			$oper_list .= ",'".str_replace('oper_','',$key)."'";
	$oper_list = substr($oper_list,1);
	if(($_GET['datefrom']) && (preg_match("/^2[0123456789]{3}-[01][0123456789]-[0123][0123456789] [012][0123456789]\:[012345][0123456789]$/",$_GET['datefrom'])))
		$filter .= " dateofevent >= '".$_GET['datefrom']."' AND";
	if(($_GET['dateto']) && (preg_match("/^2[0123456789]{3}-[01][0123456789]-[0123][0123456789] [012][0123456789]\:[012345][0123456789]$/",$_GET['dateto'])))
		$filter .= " dateofevent <= '".$_GET['dateto']."' AND";
	if($oper_list)
		$filter .= " agentid IN (".$oper_list.") AND";

	$html_out = "<FORM action=\"index.php\">";
	$html_out .= "C <INPUT name=\"datefrom\" placeholder=\"ГГГГ-ММ-ДД ЧЧ:ММ\" maxlength=\"16\"\" size=\"16\" value=\"".$_GET['datefrom']."\"> ";
	$html_out .= "по <INPUT name=\"dateto\" placeholder=\"ГГГГ-ММ-ДД ЧЧ:ММ\" maxlength=\"16\"\" size=\"16\" value=\"".$_GET['dateto']."\"> ";
	$html_out .= "<br>Операторы:<br/>".form_oper_list('oper');
	$html_out .= "<INPUT type=\"submit\">";
	$html_out .= "</FORM>";
echo $oper_list;
	echo $html_out;
?>