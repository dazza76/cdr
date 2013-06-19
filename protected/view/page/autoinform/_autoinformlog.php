<?php
function showoper($id)
{
        mysql_query("SET NAMES UTF8");
        $res = mysql_query("SELECT name FROM queue_agents WHERE agentid = $id;");
        if(mysql_num_rows($res))
                $row = mysql_fetch_array($res);
        else
                return 'Неизвестно';
        return $row[0];
}


	if(!isset($_GET["id"]))
	{
		echo "<p align=\"center\">Security breach.<br/><a href=\"javascript:close();\">Press</a></p>";
		return;
	}
	$queue_oper[NONE]='отсутствует';
	$queue_oper[3001]='Любуцина С.';
	$queue_oper[2001]='Молдаванова И.';
	$queue_oper[2002]='Сенюшкина Л.';
	$queue_oper[2003]='Агрэ Р.';
	$queue_oper[2004]='Полыковская Л.';
	$queue_oper[2005]='Магомедова А.';
	$queue_oper[2006]='Захаренкова Т.';
	$queue_oper[2007]='Теплова Г.';
	$queue_oper[2008]='Афанасьева И.';
	$queue_oper[2009]='Бурова И.';
	$queue_oper[2010]='Сенюшкина М.';
	$queue_oper[2011]='Медведева Л.';
	$queue_oper[2012]='Лукин И.';
	$queue_oper[2013]='Сарипова Л.';
	$queue_oper[2014]='Борисова О.';
	$queue_oper[2015]='Горелова О.';
	$queue_oper[2016]='Александрович Д.';
	$queue_oper[2017]='Вставская Ж.';
	$queue_oper[2018]='Гладышева Э.';
	$queue_oper[2019]='2019';
	$queue_oper[2020]='2020';
?>
<html>
<head>
<title>
Подробная информация о вызове <?php echo $_GET["id"];?>
</title>
  <style type="text/css">
   BODY {
    background: #CCCCCC;
   }
   INPUT, SELECT {
    background: #FACE8D;
   }
   a {
    font-size: 12px;
   }
   TABLE.result {
    border: 2px solid black; /* Рамка вокруг таблицы */
    border-bottom: none; /* Убираем линию снизу */
    border-collapse: collapse;
   }
   TH.result {
    text-align: center; /* Выравнивание по левому краю */
    background: black; /* Цвет фона */
    padding: 3px; /* Поля вокруг содержимого ячеек */
    color: white; /* Цвет текста */
    border: 1px solid black; /* Рамка вокруг ячеек */
    border-collapse: collapse;
   }
   TD.result {
    border-bottom: 2px solid black; /* Линия снизу */
    border-right: 1px solid black; /* Линия снизу */
    padding: 3px; /* Поля вокруг содержимого ячеек */
    border-collapse: collapse;
    text-align: center;
   }
   THEAD {
    background-color: #FFDDDD;
    font-size: 18px;
    border-bottom: 2px solid black; /* Линия снизу */
   }
   TR.failed {
    border-collapse: collapse;
    background-color: #CCCCCC;
    border-bottom: 2px solid black; /* Линия снизу */
   }
   TR.success {
    border-collapse: collapse;
    background-color: #FACE8D;
    border-bottom: 2px solid black; /* Линия снизу */
   }
  </style>
</head>
<body>
<?php
$hostname = "capital";
$username = "capital\ikt";
$password = "ntrFkjd";
$db=mssql_connect($hostname,$username,$password) OR DIE("Не могу создать соединение!");
$dbconn=mysql_connect("localhost","root","finger") OR DIE("Не могу создать соединение!");
//mssql_select_db($dbName,$db) or die('Database not selected!');
mysql_select_db("asterisk",$dbconn) or die('Database not selected!');
$query = "select * from dbo.autodialout where id=$_GET[id]";
$res = mssql_query($query) or die('Query error');
$html_out = '<table align="center" class="result">
                        <thead>
                        <tr>
			<td class="result" width="50px">ID</td>
                        <td class="result" width="150px">Дата приема</td>
                        <td class="result" width="200px">Тип вызова</td>
                        <td class="result" width="150px">Номер телефона</td>
                        <td class="result" width="150px">Результат</td>
                        <td class="result" width="75px">Попытки</td>
                        </tr>
                        </thead><tbody>';

while ($row = mssql_fetch_array($res))
{
	switch (substr($row[2],0,3))
	{
		case 'Jan':
			$mon = '01';
		break;
		case 'Feb':
			$mon = '02';
		break;
		case 'Mar':
			$mon = '03';
		break;
		case 'Apr':
			$mon = '04';
		break;
		case 'May':
			$mon = '05';
		break;
		case 'Jun':
			$mon = '06';
		break;
		case 'Jul':
			$mon = '07';
		break;
		case 'Aug':
			$mon = '08';
		break;
		case 'Sep':
			$mon = '09';
		break;
		case 'Oct':
			$mon = '10';
		break;
		case 'Nov':
			$mon = '11';
		break;
		case 'Dec':
			$mon = '12';
		break;
	};
	switch(substr($row[2],24,2))
	{
		case 'PM':
			$add_hour = '12';
			if(substr($row[2],12,2)==12)
				$add_hour = '0';
		break;
		case 'AM':
			$add_hour = '0';
			if(substr($row[2],12,2)==12)
				$add_hour = '-12';
		break;
	};
	$date=substr($row[2],4,2).".$mon.".substr($row[2],7,4).' '.(substr($row[2],12,2)+$add_hour).":".(substr($row[2],15,2));
	switch (substr($row[4],0,3))
	{
		case 'Jan':
			$mon = '01';
		break;
		case 'Feb':
			$mon = '02';
		break;
		case 'Mar':
			$mon = '03';
		break;
		case 'Apr':
			$mon = '04';
		break;
		case 'May':
			$mon = '05';
		break;
		case 'Jun':
			$mon = '06';
		break;
		case 'Jul':
			$mon = '07';
		break;
		case 'Aug':
			$mon = '08';
		break;
		case 'Sep':
			$mon = '09';
		break;
		case 'Oct':
			$mon = '10';
		break;
		case 'Nov':
			$mon = '11';
		break;
		case 'Dec':
			$mon = '12';
		break;
	};
	switch(substr($row[4],24,2))
	{
		case 'PM':
			$add_hour = '12';
		break;
		case 'AM':
			$add_hour = '0';
		break;
	};
	$calldate=substr($row[4],4,2).".$mon.".substr($row[4],7,4).' '.(substr($row[4],12,2)+$add_hour).":".(substr($row[4],15,2));
	$html_out .= "<tr class=\"success\">";
	$html_out .= "<td class=\"result\" >$row[0]</td>";
	$html_out .= "<td class=\"result\" >$calldate</td>";
	switch($row[3])
	{
		case '1':
			$html_out .= "<td class=\"result\">Страховая компания</td>";
		break;
		case '2':
			$html_out .= "<td class=\"result\">Медцентр</td>";
		break;
	};
	$html_out .= "<td class=\"result\">+7".substr($row[1],1)."</td>";
	switch($row[7])
	{
		case '99':
			$html_out .= "<td class=\"result\">Обрабатывается</td>";
		break;
		case '98':
			$html_out .= "<td class=\"result\">Карантин</td>";
		break;
		case '97':
			$html_out .= "<td class=\"result\">Неудачно</td>";
		break;
		case '96':
			$html_out .= "<td class=\"result\">Удалено из МИС</td>";
		break;
		case '95':
			$html_out .= "<td class=\"result\">Нет номера</td>";
		break;
		case '0':
			$html_out .= "<td class=\"result\">Не обрабатывался</td>";
		break;
		case '1':
			$html_out .= "<td class=\"result\">Не дослушан</td>";
		break;
		case '2':
			$html_out .= "<td class=\"result\">Дослушал/подтвердил</td>";
		break;
		case '3':
			$html_out .= "<td class=\"result\">Направлен в КЦ</td>";
		break;
//		case '4':
//			$html_out .= "<td class=\"result\">Визит отменен</td>";
//		break;
		case '5':
			$html_out .= "<td class=\"result\">Направлен в КЦ";
		break;
		default:
			$html_out .= "<td bgcolor=#990000 class=\"result\">'.$row[7].'</td>";
		break;
	};
	$html_out .= "<td class=\"result\">$row[6]</td>";
};
$html_out .= "</tbody></table><br/><br/>";
$html_out .= "<table class=\"result\" align=\"center\">";
$html_out .= "	<thead class=\"result\">
			<tr>
				<td class=\"result\">Время</td>
				<td class=\"result\">Результат</td>
				<td class=\"result\">Дополнительно</td>
			</tr>
		</thead><tbody class=\"result\">";
$querymy = "SELECT * FROM dialout WHERE origid='$_GET[id];'";
$res=mysql_query($querymy);
while ($row=mysql_fetch_array($res))
{
	switch($row[4])
	{
		case 'failed':
			$html_out .= "<tr class=\"failed\"><td class=\"result\">$row[2]</td>";
			$html_out .= "<td class=\"result\" colspan=\"2\">Безуспешно</td>";
		break;
		case 'success':
			$html_out .= "<tr class=\"success\"><td class=\"result\">$row[2]</td>";
			switch($row[5])
			{
				case '1':
					$html_out .= "<td class=\"result\" colspan=\"2\">Не дослушан</td>";
				break;
				case '2':
					$html_out .= "<td class=\"result\" colspan=\"2\">Дослушан</td>";
				break;
				case '3':
					$html_out .= "<td class=\"result\" colspan=\"2\">Визит подтвержден</td>";
				break;
				case '4':
					$html_out .= "<td class=\"result\" colspan=\"2\">Визит отменен</td>";
				break;
				case '5':
					$html_out .= "<td class=\"result\">Переведен в КЦ</td><td class=\"result\">";
					$queue_conn = mysql_connect("localhost","root","finger");
					mysql_select_db("asterisk",$queue_conn);
					$queue_query = "SELECT * FROM `call_status` WHERE `callId` = $row[7]";
					$queue_res = mysql_query($queue_query);
					while($queue_row=mysql_fetch_array($queue_res))
					{
						$html_out .= "Оператор ".$queue_oper[$queue_row[2]]."<br/>";
						$html_out .= "Ждал $queue_row[8] сек.";
					};
				break;
				default:
					$html_out .= "<td bgcolor=#990000 class=\"result\">'.$row[7].'</td>";
				break;
			};
		break;
	};
	$html_out .= "</tr>";
};
$html_out .= "</tbody><thead class=\"result\"><tr><td align=\"center\"colspan=\"3\"><input type=\"button\" value=\"Закрыть это окно\" onClick=\"window.close()\"></td></tr></thead>";
$html_out .= "</table><br/>";
echo $html_out;
?>
</body>
</html>
