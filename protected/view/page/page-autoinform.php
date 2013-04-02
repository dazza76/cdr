<?php
/**
 * page-autoinform.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this AutoinformController */

/* @var $row Autodialout */

?>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Тип вызова</div>
            <div class="labeled">
                <select name="type" size="1"  default="<?php echo $this->type; ?>">
                    <option value="" selected="selected">Любой</option>
                    <option value="1">Анализ</option>
                    <option value="2">Прием</option>
                </select>
            </div>
        </div>


        <div class="filter fl_l sep">
            <div class="label">Результат</div>
            <div class="labeled">              
                <select name="result" size="1"  default="<?php echo $this->result; ?>">
                    <option value="">Любой</option>
                    <option value="0">Не обработано</option>
                    <option value="98">Карантин</option>
                    <option value="99">В обработке</option>
                    <option value="1">Не дослушано</option>
                    <option value="2">Дослушано</option>
                    <option value="3">Подтверждено</option>
                    <option value="5">Отменено</option>
                    <option value="97">Неудачно</option>
                    <option value="96">Удалено из МИС</option>
                    <option value="95">Нет номера</option>
                </select>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Номер абонента</div>
            <div class="labeled">
                <input name="phone" type="text" placeholder="Источник" autocomplete="off" style="width: 8em;" value="<?php echo html($this->phone); ?>">
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Кол-во попыток</div>
            <div class="labeled">
                <div class="labeled">
                    <select name="retries" size="1"  default="<?php echo $this->retries; ?>">
                        <option value="" selected="selected">Неважно</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
            </div>
        </div>
    </form>
</div>



<div class="clear clear_fix" >
    <table class="grid" htable="1">
        <thead>
            <tr class="b-head">
                <th style="width: 60px;" >ID</th>
                <th style="width: 150px;">Дата приема</th>
                <th >Тип вызова</th>
                <th style="width: 150px;">Номер телефона</th>
                <th style="width: 300px;" colspan="3">Результат</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $this->fetchArray()) {
                echo "<tr>";
                echo "<td >$row[0]</td>";
                echo "<td >".FiltersValue::toFormatDate($row[2])."</td>";
                
                // type_name
                $type_name = App::Config()->autoinform['type_code'][$row[3]];
                if($type_name) {
                    $type_name .= " ({$row[3]})";
                } else {
                    $type_name = $row[3];
                }
                echo "<td>{$type_name}</td>";
//                switch ($row[3]) {
//                    case '1':
//                        echo "<td>Страховая</td>";
//                        $analis ++;
//                        break;
//                    case '2':
//                        echo "<td>Медцентр</td>";
//                        $vizit ++;
//                        break;
//                    default :
//                        echo "<td>{$row[3]}</td>";
//                        break;
//                }
                
                echo "<td>+7" . substr($row[1], 1) . "</td>";
                switch ($row[7]) {
                    case '99':
                        echo "<td>Обрабатывается</td>";
                        $in_work ++;
                        break;
                    case '98':
                        echo "<td>Карантин</td>";
                        $quarantine ++;
                        break;
                    case '97':
                        echo "<td>Неудачно</td>";
                        $failed ++;
                        break;
                    case '95':
                        echo "<td>Нет номера</td>";
                        $removed ++;
                        break;
                    case '96':
                        echo "<td>Удалено из МИС</td>";
                        $removed ++;
                        break;
                    case '0':
                        echo "<td>Не обрабатывался</td>";
                        $no_call ++;
                        break;
                    case '1':
                        echo "<td>Не дослушан</td>";
                        $uncompleted ++;
                        break;
                    case '2':
                        echo "<td>Дослушал/подтвердил</td>";
                        $completed ++;
                        break;
                    case '3':
                        echo "<td>Направлен в КЦ</td>";
                        $queued ++;
                        break;
                    case '5':
                        echo "<td>Направлен в КЦ</td>";
                        $queued ++;
                        break;
                    default:
                        echo "<td bgcolor=#990000>{$row[7]}</td>";
                        break;
                };
                switch ($row[6]) {
                    case '0':
                        echo "<td>0 попыток</td>";
                        break;
                    case '1':
                        echo "<td><a href=\"autoinformlog.php?section=log&id=$row[0]\" target=\"_blank\">1 попытка</a></td>";
                        break;
                    default:
                        echo "<td><a href=\"autoinformlog.php?section=log&id=$row[0]\" target=\"_blank\">$row[6] попытки</a></td>";
                        break;
                }
                echo "</tr>\n";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$summary = $this->numRows();

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
$table = '<table class="grid" align="center" style="width:500px;">
        <thead>
			<tr>
				<td class="head" align="center">Состояние</td>
				<td class="head" align="center">Подробно</td>
				<td class="head" align="center">Кол-во</td>
				<td class="head" align="center">Соотн.</td>
			</tr>
		</thead>
		<tr>';
if($state[0]>0) {
	$table .= '<td rowspan="'.$state[0].'" class="head">Обработка идет</td>';
}
if($no_call != 0)
{
	$table .= "		<td align=\"center\">Не обработано</td>
				<td align=\"center\">$no_call</td>
				<td align=\"center\">".round(($no_call/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($quarantine != 0)
{
	$table .= "		<td align=\"center\">Карантин</td>
				<td align=\"center\">$quarantine</td>
				<td align=\"center\">".round(($quarantine/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($in_work != 0)
{
	$table .= "		<td align=\"center\">В обработке</td>
				<td align=\"center\">$in_work</td>
				<td align=\"center\">".round(($in_work/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($state[0] != 0)
{
	$table .= "		<td align=\"center\">Итого</td>
				<td align=\"center\">".($no_call+$quarantine+$in_work)."</td>
				<td align=\"center\">".round(($no_call+$quarantine+$in_work)/$summary*100,2)." %</td>
			</tr>
			<tr>";
}
if($state[1]>0)
	$table .= "<td rowspan=\"$state[1]\" class=\"head\" >Обработка завершена</td>";
if($uncompleted != 0)
{
	$table .= "		<td align=\"center\">Не дослушано</td>
				<td align=\"center\">$uncompleted</td>
				<td align=\"center\">".round(($uncompleted/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($completed != 0)
{
	$table .= "		<td align=\"center\">Дослушано</td>
				<td align=\"center\">$completed</td>
				<td align=\"center\">".round(($completed/$summary*100),2)." %</td>
			</tr>
			<tr >";
}
if($confirmed != 0)
{
	$table .= "		<td align=\"center\">Подтверждено</td>
				<td align=\"center\">$confirmed</td>
				<td align=\"center\">".round(($confirmed/$summary*100),2)." %</td>
			</tr>
			<tr >";
}
if($unconfirmed != 0)
{
	$table .= "		<td align=\"center\">Отменено</td>
				<td align=\"center\">$unconfirmed</td>
				<td align=\"center\">".round(($unconfirmed/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($queued != 0)
{
	$table .= "		<td align=\"center\">Переведено в КЦ</td>
				<td align=\"center\">$queued</td>
				<td align=\"center\">".round(($queued/$summary*100),2)." %</td>
			</tr>
                        <tr>";
}
if($state[1] != 0)
{
	$table .= "		<td align=\"center\">Итого</td>
				<td align=\"center\">".($completed+$uncompleted+$queued+$confirmed+$unconfirmed)."</td>
				<td align=\"center\">".round(($completed+$uncompleted+$queued+$confirmed+$unconfirmed)/$summary*100,2)." %</td>
			</tr>";
}
if($state[2]>0)
	$table .= "<td rowspan=\"$state[2]\" class=\"head\" >Обработка отменена</td>";
if($failed != 0)
{
	$table .= "		<td align=\"center\">Неудачно</td>
				<td align=\"center\">$failed</td>
				<td align=\"center\">".round(($failed/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($removed != 0)
{
	$table .= "		<td align=\"center\">Удалено из МИС</td>
				<td align=\"center\">$removed</td>
				<td align=\"center\">".round(($removed/$summary*100),2)." %</td>
			</tr>
			<tr>";
}
if($state[2] != 0)
{
	$table .= "		<td align=\"center\">Итого</td>
				<td align=\"center\">".($failed+$removed)."</td>
				<td align=\"center\">".round(($failed+$removed)/$summary*100,2)." %</td>
			</tr>
			<tr>";
}
$table .= "		<thead>
				<tr>
					<td class=\"head\">Суммарный итог</td>
					<td colspan=\"3\">$summary</td>
				</tr>
			</thead>";
$table .= "</table><br />";

?>


<div class="filters clear clear_fix bigblock">
    <?php    echo $table; ?>
</div>


<div class="filters clear_fix miniblock of_h" >
    <table class="grid" htable="1">
        <thead>
            <tr>
                <th >ID</th>
                <th >Дата приема</th>
                <th >Тип вызова</th>
                <th >Номер телефона</th>
                <th colspan="3">Результат</th>
            </tr>
        </thead>
    </table>
</div>