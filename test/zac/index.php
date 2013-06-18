<?php
header('Content-type: text/html; charset=UTF-8');
if(!$_GET[year])
{
//	$output .= "<h1 align=center>Введите параметры.</h1>";
	$_GET["month"] = date('m');
	$_GET["year"] = date('Y');
}
$script_start = microtime(true);
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'asterix';

$month = array(
	"01"=>"Январь",
	"02"=>"Февраль",
	"03"=>"Март",
	"04"=>"Апрель",
	"05"=>"Май",
	"06"=>"Июнь",
	"07"=>"Июль",
	"08"=>"Август",
	"09"=>"Сентябрь",
	"10"=>"Октябрь",
	"11"=>"Ноябрь",
	"12"=>"Декабрь"
);
$year = array(
	"2012"=>"2012",
	"2013"=>"2013",
	"2014"=>"2014",
	"2015"=>"2015",
	"2016"=>"2016"
);
$first_day = "$_GET[year]-$_GET[month]-01";
$last_day = "$_GET[year]-$_GET[month]-".date("t",strtotime($first_day));
$weekday = array("Вс", "Пн","Вт","Ср","Чт","Пт","Сб");

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

$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);

?>
<html><body><style>select { text-align: center; } a { text-decoration: none; } td.data:hover {background:#FACE8D; } tr.data:hover {background:#FACE8D; } table {border: 0px; } td { border: solid 1px; } input.time {width: 30px;}</style>
<script language="javascript">
function oper_copy_select(where)
{
	element = document.getElementById('copy_list_' + where);
	from = element.value;

	if(where == from)
		exit();

	if(confirm('Вы уверены? Действие нельзя отменить!'))
	{
		move_on = 'copy.php?from=' + from + '&where=' + where + '&date=<?php echo "$_GET[year]-$_GET[month]";?>';
		document.location = move_on;
	}
	else
	{
		move_on = 'index.php';
		document.location = move_on;
	}
}
function oper_copy(oper)
{
        var oper_copy_select = function(where)
        {
                alert('Test');
        }
	element = document.getElementById('oper_' + oper);
	output = "<SELECT name=\"test\" onchange=\"oper_copy_select('" + oper + "')\" id=\"copy_list_" + oper + "\">";
	output += "<OPTION value=\"none\">Скопировать</OPTION>";
	output += "<?php $tmp = mysql_query("SELECT DISTINCT agentid FROM queue_agents ORDER BY name ASC;") or die(mysql_error()); while($tmp_row = mysql_fetch_array($tmp)) { echo("<OPTION value=\\\"$tmp_row[0]\\\">".showoper($tmp_row[0])."</OPTION>"); }; ?>";
	output += "</SELECT>";
	element.innerHTML += output;
	element.onclick = '';
//	alert(element.innerHTML);
}
function plan_submit(cell)
{
	element = document.getElementById(cell + '_plan_value');
	value = element.value;
	move_on = 'replan.php?cell=' + cell + '&value=' + value;
	document.location = move_on;
}
function plan_edit(cell)
{
        var plan_submit = function(cell)
        {
                alert('Test');
        }
	element = document.getElementById(cell + '_plan');
	temp = element.innerHTML;
	element.innerHTML = "<input placeholder=\"ЧЧ\" type=text id=\"" + cell + "_plan_value\" value=" + temp + " class=\"time\"/><br/><input type=\"button\" value=\"OK\" onclick=\"plan_submit('" + cell + "')\" />";
	element.onclick = '';
}
function day_submit(cell,type)
{
	if(type == 'job')
	{
		hour = document.getElementById(cell + ':' + type + '_value_hour').value;
		minute = document.getElementById(cell + ':' + type + '_value_min').value;
		length = document.getElementById(cell + ':' + type + '_value_length').value;
		quant = document.getElementById(cell + ':' + type + '_value_quant').value;
		data = hour + ":" + minute + ":" + length + ":" + quant;
	}
	else
	{
		data = document.getElementById(cell + ':' + type + '_value').value;
	}
	move_on = 'reschedule.php?view=<?php echo $_GET[year] . '-' . $_GET[month];?>&data=' + cell + ':' + type + ':' + data;
	document.location = move_on;

}
function day_select(cell)
{
	var day_submit = function(cell,type)
	{
		alert('Test');
	}
	list = document.getElementById(cell + 'select');
	value = list.value;
	switch(value)
	{
		case 'off':
			element = document.getElementById(cell);
			output = '<select id=\"'+ cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value="off" selected>Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':off_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = cell + ':off_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell +'\',\'off\')\" />';
		break;
		case 'ill':
			element = document.getElementById(cell);
			output = '<select id=\"'+ cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\" selected>Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\" selected>Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':ill_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = cell + ':ill_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell +'\',\'ill\')\" />';
		break;
		case 'vac':
			element = document.getElementById(cell);
			output = '<select id=\"'+ cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\" selected>Выходной</option>';
			output += '<option value=\"vac\" selected>Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\">Смена</option>';
			output += '</select>';
			output += 'Дней: <input id=\"' + cell + ':vac_value\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			id = ':vac_text';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell +'\',\'vac\')\" />';
		break;
		case 'job':
			output = '<select id=\"'+ cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
			output += '<option value=\"off\">Выходной</option>';
			output += '<option value=\"vac\">Отпуск</option>';
			output += '<option value=\"ill\">Больничный</option>';
			output += '<option value=\"job\" selected>Смена</option>';
			output += '</select><br/>';
			output += 'Начало:<br/><input id=\"' + cell + ':job_value_hour\" type=\"text\" placeholder=\"ЧЧ\" class=\"time\" maxlength=\"2\" value=\"08\"/>:<input id=\"' + cell + ':job_value_min\" type=\"text\" placeholder=\"ММ\" class=\"time\" maxlength=\"2\" value=\"00\"/><br/>Длительность:<br/>';
			output += '<select id=\"'+ cell + ':job_value_length\">';
			output += '<option value=\"6\">6 часов</option>';
			output += '<option value=\"8\">8 часов</option>';
			output += '<option value=\"12\">12 часов</option>';
			output += '<option value=\"24\">24 часа</option>';
			output += '</select><br/>';
			output += 'Дней: <input id=\"' + cell + ':job_value_quant\" type=\"text\" class=\"time\" maxlength=\"2\" placeholder=\"ДД\" value=\"1\"/><br/>';
			output += '<input type=\"submit\" value=\"Сохранить\" onclick=\"day_submit(\'' + cell +'\',\'job\')\" />';
		break;
	}
	element.innerHTML = output;
}
function day_click(cell,type)
{
	var day_select = function(cell)
	{
		alert('Test');
	}
	element = document.getElementById(cell);
	if(!(element.innerHTML.indexOf('<select>')+1))
	{
		var output = '<select id=\"'+ cell + 'select\"onChange=\"day_select(\'' + cell + '\')\">';
		output += '<option value="off"';
		if(type == 'off')
			output += ' selected';
		output += '>Выходной</option>';
		output += '<option value=\"vac\"';
		if(type == 'vac')
			output += ' selected';
		output += '>Отпуск</option>';
		output += '<option value=\"ill\"';
		if(type == 'ill')
			output += ' selected';
		output += '>Больничный</option>';
		output += '<option value=\"job\"';
		if(type == 'job')
			output += ' selected';
		output += '>Смена</option>';
		output += '</select>';
	}
	element.innerHTML = output;
	element.onclick = '';
}
</script>
<?php
$output = "<h1 align=center>Расписание работы сотрудников Контакт-центра</h1><h4 align=center>&copy;ИКТ Интеграция</br>2010-".date('Y')."</h4><form action=\"index.php?year=$_GET[year]&month=$_GET[month]\">";
$output .= "<table border=\"1\" align=center>";
$output .= "<tr><td align=center>Месяц</td><td align=center>Год</td></tr>";
$output .= "<tr><td align=center>";
$output .= "<select name=month>";
foreach($month as $key=>$value)
{
	$output .= "<option value=$key";
	if($_GET["month"]==$key)
		$output .= " selected";
	$output .= ">$value</option>";
}
$output .= "</select>";
$output .= "</td>";
$output .= "<td align=center>";
$output .= "<select name=year>";
foreach($year as $key=>$value)
{
	$output .= "<option value=$key";
	if($_GET["year"]==$key)
		$output .= " selected";
	$output .= ">$value</option>";
}
$output .= "</select>";
$output .= "</td></tr>";
$nextmonth = ($_GET[month] + 1);
if($nextmonth == 13)
	$nextmonth = 1;
$prevmonth = ($_GET[month] - 1);
if($prevmonth == 0)
	$prevmonth = 12;
$nextyear = $_GET[year];
$prevyear = $_GET[year];
if($nextmonth == 1)
	$nextyear += 1;
if($prevmonth == 12)
	$prevyear -= 1;
if($nextmonth < 10)
	$nextmonth = "0$nextmonth";
if($prevmonth < 10)
	$prevmonth = "0$prevmonth";
$output .= "<tr><td colspan=3 align=center><input type=button value=\"<<\" onclick=\"window.location = '$_SERVER[PHP_SELF]?month=$prevmonth&year=$prevyear';\"/><input type=submit value=\"Открыть\" /><input type=button value=\">>\" onclick=\"window.location = '$_SERVER[PHP_SELF]?month=$nextmonth&year=$nextyear';\"/>
</td></tr>";
$output .= "</table></form>";

//else
{
	$output .= "<table align=\"center\">";
	$output .= "<tr><td>Дата</td>";
	for($i=1; $i<=(int)date('t',strtotime("$_GET[year]-$_GET[month]-01"));$i++)
	{
		$output .= "<td align=\"center\" width=\"40px\"";
		if((date('w',strtotime("$_GET[year]-$_GET[month]-$i")) == '6') || (date('w',strtotime("$_GET[year]-$_GET[month]-$i")) == '0'))
			$output .= "bgcolor=\"#FACE8D\"";
		if((date('Y-m-d') == "$_GET[year]-$_GET[month]-$i")||(date('Y-m-d') == "$_GET[year]-$_GET[month]-0$i"))
			$output .= "bgcolor=\"#99FFFF\"";
		$output .= ">$i.$_GET[month]<br/>".$weekday[date('w',strtotime("$_GET[year]-$_GET[month]-$i"))]."</td>";
	}
	$output .= "<td align=\"center\">Ставка:</td>";
	$output .= "<td align=\"center\">План:</td>";
	$tempquery = "SELECT agentid FROM queue_agents ORDER BY name;";
	$tempres = mysql_query($tempquery) or die(mysql_error());
	while($temprow = mysql_fetch_array($tempres))
	{
		$output .= "<tr class=data><td width=\"150px\" id=\"oper_$temprow[0]\" onClick=\"oper_copy($temprow[0])\">";
		$output .= showoper($temprow[0]);
		$output .= "</td>";

		for($i=1; $i<=(int)date('t',strtotime("$_GET[year]-$_GET[month]-01"));$i++)
		{
			if($i < 10)
				$i = "0$i";
			$day = "$_GET[year]-$_GET[month]-$i";
			$query="SELECT * FROM timetable WHERE agentid_day = '$temprow[0].$day';";
			$res = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_array($res);
			if(date('Y-m-d') == $day)
				$color = "#99FFFF";
			else
				unset($color);
			switch($row[event])
			{
				case 'job':
					if(!$color)
						$color = "#FF9999";
					$all_day = FALSE;
					if($row[duration] == '24')
						$all_day = TRUE;
					$output .= "<td class=data align=center bgcolor=$color id=\"$day:$temprow[0]\" onClick=\"day_click('$day:$temprow[0]','job')\"";
					if($all_day)
					{
						$i++;
						$output .= "colspan=\"2\"";
					}
					$output .= ">".date('H:i',strtotime($row[start]))."<br/>$row[duration] ч.</td>";
				break;
				case 'ill':
					if(!$color)
						$color = "#FF99FF";
					$output .= "<td class=data align=center bgcolor=$color id=\"$day:$temprow[0]\" onClick=\"day_click('$day:$temprow[0]','ill')\">Б</td>";
				break;
				case 'vac':
					if(!$color)
						$color = "#FFFF99";
					$output .= "<td class=data align=center bgcolor=$color id=\"$day:$temprow[0]\" onClick=\"day_click('$day:$temprow[0]','vac')\">О</td>";
				break;
				default:
					if(!$color)
						$color = "#99FF99";
					$output .= "<td title=\"".showoper($temprow[0])."\n".date('d.m.Y',strtotime($day))." (".$weekday[date('w',strtotime($day))].")\" class=data align=center bgcolor=$color id=\"$day:$temprow[0]\" onClick=\"day_click('$day:$temprow[0]','off')\">В</td>";
				break;
			}
		}
		$query = "SELECT totaltime FROM timeplan WHERE SUBSTR(agentid_month,1,4) = '$temprow[0]' AND SUBSTR(agentid_month,6) = '$_GET[month]_$_GET[year]';";
		$sumres = mysql_query($query);
		$sumarr = @mysql_fetch_array($sumres);
		if(!$sumarr[0])
			$sumarr[0]=0;
		$output .= "<td align=\"right\" bgcolor=\"#FFCC33\" onClick=\"plan_edit('$temprow[0].".$_GET[month]."_".$_GET[year]."');\" id=\"$temprow[0].$_GET[month]_$_GET[year]_plan\">$sumarr[0]</td>";
		$query = "SELECT SUM(duration) FROM timetable WHERE SUBSTR(agentid_day,1,4) = '$temprow[0]' AND FROM_UNIXTIME(UNIX_TIMESTAMP(SUBSTR(agentid_day,6))) BETWEEN '$first_day' AND '$last_day';";
		$sumres = mysql_query($query);
		$sumarr = @mysql_fetch_array($sumres);
		if(!$sumarr[0])
			$sumarr[0]=0;
		$output .= "<td align=\"right\" bgcolor=\"#FFCC33\">$sumarr[0]</td>";
	}
	$output .= "</tr>";
	$output .= "</table>";
}

//echo "<h4 align=\"center\">Время формирования: ".round((microtime(true)-$script_start),5)."</h4>";
echo $output;
?>

