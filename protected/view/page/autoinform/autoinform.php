<html>
<head>
<title>
Статистика работы автоинформатора
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
   TD.result a{
    text-align: center;
    font-size:16px;
   }
   THEAD {
    background-color: #FFDDDD;
    font-size: 18px;
    border-bottom: 2px solid black; /* Линия снизу */
   }
   TR.result:nth-of-type(odd) {
    border-collapse: collapse;
    background-color: #CCCCCC;
    border-bottom: 2px solid black; /* Линия снизу */
   }
   TR.result:nth-of-type(even) {
    border-collapse: collapse;
    background-color: #FACE8D;
    border-bottom: 2px solid black; /* Линия снизу */
   }
  </style>
</head>
<body>
<?php
include('header.php');
?>
<h3 align="center">Система анализа работы автоинформатора</h3>
<h3 align="center">&copy;2010-2012 ИКТ Интеграция</h3>
<form align="center" action="autoinform.php" method="get">
<table class="result" align="center" border="1">
<thead class="result">
<td class="result">
Дата вызова
</td>
<td class="result">
Тип вызова
</td>
<td class="result">
Результат
</td>
<td class="result">
Номер абонента
</td>
<td class="result">
Кол-во попыток
</td>
</thead>
<tr align="center" class="result">
<td align="center" class="result">
<script src="calendar.js"></script>
<table border=0 align="center">
    <tr>
      <td align="center" width="150px"> Начиная с </td>
      <td align="center" colspan="2"> Дата: <input size="8" name="fromdate" type="text" value="<?php echo $_GET["fromdate"]?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" >
      </td>
      <td align="center" >Час:Мин</td>
      <td align="center"><input name="fromhour" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["fromhour"]))	echo $_GET["fromhour"];	else echo "00";?>">
        :
      <td align="center"><input name="frommin" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["frommin"]))	echo $_GET["frommin"];	else echo "00";?>">
      </td>
      </tr>
      <tr>
      <td align="center"> Заканчивая </td>
      <td align="center" colspan="2"> Дата: <input size="8" name="todate" type="text" value="<?php echo $_GET["todate"]?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)" ></td>
      <td align="center" >Час:Мин</td>
      <td align="center"><input name="tohour" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["tohour"]))	echo $_GET["tohour"];	else echo "00";?>">
        :
      <td align="center"><input name="tomin" type="text" maxlength="2" size="1" value="<?php	if (isset($_GET["tomin"]))	echo $_GET["tomin"];	else echo "00";?>">
      </td>
    </tr>
</table>

<td class="result">
<select name="type">
<option value="any">Любой</option>
<option value=1>Анализ</option>
<option value=2>Прием</option>
</select>
</td>
<td class="result">
<select name="result">
<option value="any">Любой</option>
<option value=0>Не обработано</option>
<option value=98>Карантин</option>
<option value=99>В обработке</option>
<option value=1>Не дослушано</option>
<option value=2>Дослушано</option>
<option value=3>Подтверждено</option>
<option value=4>Отменено</option>
<option value=5>Переведено в КЦ</option>
<option value=97>Неудачно</option>
<option value=96>Удалено из МИС</option>
</select>
</td>
<td class="result">
+7<input name=phone />
</td>
<td class="result">
<input type="radio" value="any" name="retries" checked />Неважно<br/>
<input type="radio" value="0"  name="retries" />0
<input type="radio" value="1"  name="retries" />1<br/>
<input type="radio" value="2"  name="retries" />2
<input type="radio" value="3"  name="retries" />3<br/>
</td>
</tr>
<tr>
<thead class="result">
<td class="result" colspan="5" align="center" valign="center"> <input type=submit value="Поиск" /> </td>
</thead>
</table>
</form>
<?php
$analis=0;
$vizit=0;
$summary=0;
$no_call=0;
$confirmed=0;
$completed=0;
$unconfirmed=0;
$in_work=0;
$quarantine=0;
$failed=0;
$queued=0;
$removed=0;
$uncompleted=0;
$hostname = "capital";
$username = "capital\ikt";
$password = "ntrFkjd";
//$dbName = "";
$fromdate .= $_GET["fromdate"].' '.$_GET["fromhour"].':'.$_GET["frommin"];
$todate .= $_GET["todate"].' '.$_GET["tohour"].':'.$_GET["tomin"];
$db=mssql_connect($hostname,$username,$password) OR DIE("Не могу создать соединение!");
//mssql_select_db($dbName,$db) or die('Database not selected!');
$query = "select * from dbo.autodialout order by datetotell;";
$res = mssql_query($query) or die('Query error');
$html_out = '<table align="center" class="result">
                        <thead>
                        <tr>
			<td class="result" width="50px">ID</td>
                        <td class="result" width="150px">Дата приема</td>
                        <td class="result" width="200px">Тип вызова</td>
                        <td class="result" width="150px">Номер телефона</td>
                        <td class="result" width="250px" colspan="3">Результат</td>
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
	if(
		(($todate==" 00:00")||(strtotime($date) <= strtotime($todate)))&&(($fromdate==" 00:00")||(strtotime($date) >= strtotime($fromdate)))&&(($_GET["result"]=="any")||($row[7]==$_GET["result"]))&&(($_GET["type"]=="any")||($_GET["type"]==$row[3]))&&(($_GET["phone"]=="")||($_GET["phone"]==substr($row[1],1)))&&(($_GET["retries"]==$row[6])||($_GET["retries"]=="any"))
	)
	{
		$summary++;
		$html_out .= "<tr class=\"result\">";
		$html_out .= "<td class=\"result\" >$row[0]</td>";
		$html_out .= "<td class=\"result\" >$calldate</td>";
		switch($row[3])
		{
			case '1':
				$html_out .= "<td class=\"result\">Страховая</td>";
				$analis++;
			break;
			case '2':
				$html_out .= "<td class=\"result\">Медцентр</td>";
				$vizit++;
			break;
		};
		$html_out .= "<td class=\"result\">+7".substr($row[1],1)."</td>";
		switch($row[7])
		{
			case '99':
				$html_out .= "<td class=\"result\">Обрабатывается</td>";
				$in_work++;
			break;
			case '98':
				$html_out .= "<td class=\"result\">Карантин</td>";
				$quarantine++;
			break;
			case '97':
				$html_out .= "<td class=\"result\">Неудачно</td>";
				$failed++;
			break;
			case '95':
				$html_out .= "<td class=\"result\">Нет номера</td>";
				$removed++;
			break;
			case '96':
				$html_out .= "<td class=\"result\">Удалено из МИС</td>";
				$removed++;
			break;
			case '0':
				$html_out .= "<td class=\"result\">Не обрабатывался</td>";
				$no_call++;
			break;
			case '1':
				$html_out .= "<td class=\"result\">Не дослушан</td>";
				$uncompleted++;
			break;
			case '2':
				$html_out .= "<td class=\"result\">Дослушал/подтвердил</td>";
				$completed++;
			break;
//			case '3':
//				$html_out .= "<td class=\"result\">Визит подтвержден</td>";
//				$confirmed++;
//			break;
//			case '4':
//				$html_out .= "<td class=\"result\">Визит отменен</td>";
//				$unconfirmed++;
//			break;
			case '3':
				$html_out .= "<td class=\"result\">Направлен в КЦ</td>";
				$queued++;
			break;
			case '5':
				$html_out .= "<td class=\"result\">Направлен в КЦ</td>";
				$queued++;
			break;
			default:
				$html_out .= "<td bgcolor=#990000 class=\"result\">'.$row[7].'</td>";
			break;
		};
		switch($row[6])
		{
			case '0':
				$html_out .= "<td class=\"result\">$row[6] попыток</td>";
			break;
			case '1':
				$html_out .= "<td class=\"result\"><a href=\"autoinformlog.php?id=$row[0]\" target=\"_blank\">1 попытка</a></td>";
			break;
			default:
				$html_out .= "<td class=\"result\"><a href=\"autoinformlog.php?id=$row[0]\" target=\"_blank\">$row[6] попытки</a></td>";
			break;
		};
	};
};
$html_out .= "</tbody></table>";
if($no_call != 0)
	$state[0]++;
if($in_work != 0)
	$state[0]++;
if($quarantine != 0)
	$state[0]++;
if($failed != 0)
	$state[2]++;
if($removed != 0)
	$state[2]++;
if($completed != 0)
	$state[1]++;
if($uncompleted != 0)
	$state[1]++;
if($confirmed != 0)
	$state[1]++;
if($unconfirmed != 0)
	$state[1]++;
if($queued != 0)
	$state[1]++;
for($i=0;$i<3;$i++)
	if($state[$i]>0)
		$state[$i]++;
$table = "<table class=\"result\" align=\"center\" width=\"500px\" border=\"1\">";
$table .= "	<thead class=\"result\">
			<tr class\"result\">
				<td class=\"result\" align=\"center\">Состояние</td>
				<td class=\"result\" align=\"center\">Подробно</td>
				<td class=\"result\" align=\"center\">Кол-во</td>
				<td class=\"result\" align=\"center\">Соотн.</td>
			</tr>
		</thead>
		<tr class=\"result\">";
if($state[0]>0)
	$table .= "<td class=\"result\" rowspan=\"$state[0]\" bgcolor=\"#FFDDDD\">Обработка<br/>идет</td>";
if($no_call != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Не обработано</td>
				<td class=\"result\" align=\"center\">$no_call</td>
				<td class=\"result\" align=\"center\">".round(($no_call/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($quarantine != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Карантин</td>
				<td class=\"result\" align=\"center\">$quarantine</td>
				<td class=\"result\" align=\"center\">".round(($quarantine/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($in_work != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">В обработке</td>
				<td class=\"result\" align=\"center\">$in_work</td>
				<td class=\"result\" align=\"center\">".round(($in_work/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($state[0] != 0)
{
	$table .= "		<td class=\"result\" bgcolor=\"#FFDDDD\">Итого</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".($no_call+$quarantine+$in_work)."</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".round(($no_call+$quarantine+$in_work)/$summary*100,2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($state[1]>0)
	$table .= "<td class=\"result\" rowspan=\"$state[1]\" bgcolor=\"#FFDDDD\">Обработка<br/>завершена</td>";
if($uncompleted != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Не дослушано</td>
				<td class=\"result\" align=\"center\">$uncompleted</td>
				<td class=\"result\" align=\"center\">".round(($uncompleted/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($completed != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Дослушано</td>
				<td class=\"result\" align=\"center\">$completed</td>
				<td class=\"result\" align=\"center\">".round(($completed/$summary*100),2)." %</td>
			</tr>
			<tr  class=\"result\">";
}
if($confirmed != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Подтверждено</td>
				<td class=\"result\" align=\"center\">$confirmed</td>
				<td class=\"result\" align=\"center\">".round(($confirmed/$summary*100),2)." %</td>
			</tr>
			<tr  class=\"result\">";
}
if($unconfirmed != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Отменено</td>
				<td class=\"result\" align=\"center\">$unconfirmed</td>
				<td class=\"result\" align=\"center\">".round(($unconfirmed/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($queued != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Переведено в КЦ</td>
				<td class=\"result\" align=\"center\">$queued</td>
				<td class=\"result\" align=\"center\">".round(($queued/$summary*100),2)." %</td>
			</tr>
                        <tr class=\"result\">";
}
if($state[1] != 0)
{
	$table .= "		<td class=\"result\" bgcolor=\"#FFDDDD\">Итого</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".($completed+$uncompleted+$queued+$confirmed+$unconfirmed)."</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".round(($completed+$uncompleted+$queued+$confirmed+$unconfirmed)/$summary*100,2)." %</td>
			</tr>";
}
if($state[2]>0)
	$table .= "<td class=\"result\" rowspan=\"$state[2]\" bgcolor=\"#FFDDDD\">Обработка<br/>отменена</td>";
if($failed != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Неудачно</td>
				<td class=\"result\" align=\"center\">$failed</td>
				<td class=\"result\" align=\"center\">".round(($failed/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($removed != 0)
{
	$table .= "		<td class=\"result\" align=\"center\">Удалено из МИС</td>
				<td class=\"result\" align=\"center\">$removed</td>
				<td class=\"result\" align=\"center\">".round(($removed/$summary*100),2)." %</td>
			</tr>
			<tr class=\"result\">";
}
if($state[2] != 0)
{
	$table .= "		<td class=\"result\" bgcolor=\"#FFDDDD\">Итого</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".($failed+$removed)."</td>
				<td class=\"result\" bgcolor=\"#FFDDDD\">".round(($failed+$removed)/$summary*100,2)." %</td>
			</tr>
			<tr class=\"result\">";
}
$table .= "		<thead class=\"result\">
				<tr class\"result\" height=\"50px\">
					<td class=\"result\" colspan=\"2\">Суммарный итог</td>
					<td class=\"result\" colspan=\"2\">$summary</td>
				</tr>
			</thead>";
$table .= "</table><br />";
if ($summary != 0)
	echo $table;
echo $html_out;
?>
</body>
</html>
