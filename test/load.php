<?php
header('Content-type: text/html; charset=utf-8');
?>
<html>
    <head>
        <title>Статистика операторов</title>
    </head>
    <body>
        <?
        include 'header.php';
        ?>
        <h1 align="center">Система анализа загруженности операторов</h1>
        <h3 align="center">&copy;2010-2011</h3>

        <?php
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'cmri';
        $db = mysql_connect($db_host, $db_user, $db_pass);
        mysql_select_db($db_name);
        $tempfromdate = substr($_GET["fromdate"], 6, 4) . "-" . substr($_GET["fromdate"], 3, 2) . "-" . substr($_GET["fromdate"], 0, 2) . " " . $_GET["fromhour"] . ":" . $_GET["frommin"];
        $temptodate = substr($_GET["todate"], 6, 4) . "-" . substr($_GET["todate"], 3, 2) . "-" . substr($_GET["todate"], 0, 2) . " " . $_GET["tohour"] . ":" . $_GET["tomin"];
        $tempto = "";
        $tempfrom = "";
        $temp = "1";
        if (($fromdate != ' 00:00') && ($fromdate != ' :')) {
            $temp .= " AND timestamp >= '$tempfromdate'";
        };
        if (($todate != ' 00:00') && ($todate != ' :')) {
            $temp .= " AND timestamp <= '$temptodate'";
        };

        $sql_req = "SELECT * from call_status WHERE $temp;";
        $result = mysql_query($sql_req, $db);
        ?>

        <form align="center" action="load.php" method="get">
            <table align="center" border=1>
                <tr align="center">

<?php
include 'calendar.php';
?>

                </tr>
                <tr>
                </tr>
                <tr>
                    <td align="center" valign="center"> <input type=submit value="Поиск" /> </td> </tr> 
            </table>
        </form>
<?
//	require 'users_cdr.php'; 
?>
        <?php
        $fromdate = $_GET["fromdate"] . " " . $_GET["fromhour"] . ":" . $_GET["frommin"];
        $todate = $_GET["todate"] . " " . $_GET["tohour"] . ":" . $_GET["tomin"];
        $html_out = '<table align="center" border="1">
			<thead height="50px">
			<tr height="50px" align="center">
			<td width="200px">Оператор</td>
			<td>Количество вызовов</td>
			<td>Время разговоров, мин</td>
			<td>Ср. время разг., сек</td>
			<td>Ср. время подн. тр., сек</td>
			<td>Исходящих</td>
			</tr>
			</thead>';
        $lost = 0;
        while ($row = mysql_fetch_array($result)) {
            if (
            ((strtotime($row[4]) >= strtotime($fromdate)) || ($fromdate == " 00:00")) && (($todate == " 00:00") || (strtotime($row[4]) <= strtotime($todate)))) {
                $oper[$row[2]][0]++;
                $oper[$row[2]][2] = showoper($row[2]);
                $oper[$row[2]][1]+=$row[10];
                $tempquery = "SELECT AVG(ringtime) FROM call_status WHERE $temp AND memberId = '$row[2]';";
                $tempres = mysql_query($tempquery);
                $temparr = mysql_fetch_array($tempres);
                $oper[$row[2]][3] = round($temparr[0]);
                $tempquery = "SELECT COUNT(uniqueid) FROM cdr WHERE " . str_replace("timestamp", "calldate", $temp) . " AND userfield = '$row[2]';";
                $tempres = mysql_query($tempquery) or die(mysql_error());
                $temparr = mysql_fetch_array($tempres);
                $oper[$row[2]][4] = round($temparr[0]);
            }
        }
        for ($i = 1000; $i <= 2000; $i++) {
            if ($oper[$i][0] != 0) {
                $html_out .= '<tr>';
                $html_out .= '<td>' . $oper[$i][2] . '</td>';
                $html_out .= '<td>' . $oper[$i][0] . '</td>';
                $html_out .= '<td>' . round($oper[$i][1] / 60, 2) . '</td>';
                $html_out .= '<td>' . round($oper[$i][1] / $oper[$i][0]) . '</td>';
                $html_out .= '<td>' . $oper[$i][3] . '</td>';
                $html_out .= '<td>' . $oper[$i][4] . '</td>';
                $html_out .= '</tr>';
            }
        }
        $html_out .= '</tr></table><br/>';
        echo $html_out;
        ?>
        <br/>
        <br/>
        <br/>
        <?php
        $graph = '<center><img src="graph_load.php?fromday=';
        $graph .= $_GET["fromday"];
        $graph .= '&frommonth=';
        $graph .= $_GET["frommonth"];
        $graph .= '&fromyear=';
        $graph .= $_GET["fromyear"];
        $graph .= '&fromhour=';
        $graph .= $_GET["fromhour"];
        $graph .= '&frommin=';
        $graph .= $_GET["frommin"];
        $graph .= '&today=';
        $graph .= $_GET["today"];
        $graph .= '&tomonth=';
        $graph .= $_GET["tomonth"];
        $graph .= '&toyear=';
        $graph .= $_GET["toyear"];
        $graph .= '&tohour=';
        $graph .= $_GET["tohour"];
        $graph .= '&tomin=';
        $graph .= $_GET["tomin"];
        $graph .= '&scale=';
        $graph .= $_GET["scale"];
        $graph .= '"><center>';

//echo $graph;
        ?>
    </body>
</html>
