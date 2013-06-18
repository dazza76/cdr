<?php
function showoperslist()
{
	$result = "<option value=\"any\">Любой</option>";
        mysql_query("SET NAMES UTF8");
        $res = mysql_query("SELECT name,agentid FROM queue_agents");
        while($row = mysql_fetch_array($res))
	{
		$result .= "<option value=\"$row[agentid]\">$row[name]</option>";
	}

        return $result;
}

echo showoperslist();
?>
