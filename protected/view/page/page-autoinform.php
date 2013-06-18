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
                    <?php
                    foreach (App::Config()->autoinform['type'] as $key => $val) {
                        echo "<option value=\"$key\">" . html($val) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="filter fl_l sep">
            <div class="label">Результат</div>
            <div class="labeled">
                <select name="result" size="1"  default="<?php echo $this->result; ?>">
                    <option value="">Любой</option>
                    <?php
                    foreach (App::Config()->autoinform['result'] as $key => $result) {
                        $val = explode(";", $result);
                        echo "<option value=\"$key\">" . html($val[1]) . "</option>";
                    }
                    ?>
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
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>


        <div class="filter fl_r">
            <div class="labeled" style="margin-top: 24px;">
                <span>
                    <input type="hidden" id="export_type" name="export" value="1" />
                    <a id="button-export" href="" class="icon icon_excel">Экспорт</a>
                </span>
            </div>
        </div>
    </form>
</div>



<div class="clear clear_fix" >
    <table class="grid">
        <thead>
            <tr>
                <td class="head"  style="width: 60px;" >ID</td>
                <td class="head"  style="width: 150px;">Дата приема</td>
                <td class="head"  >Тип вызова</td>
                <td class="head"  style="width: 150px;">Номер телефона</td>
                <td class="head"  style="width: 300px;" colspan="3">Результат</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $_result_arr_test = array();
            $_result_state = array();

            while ($row = $this->fetchArray() ) {
                $date = @new DateTime($row[2]);
                $date =($date) ? $date : $row[2] ;


                echo "<tr>";
                echo "<td >$row[0]</td>";
                echo "<td >" . /* FiltersValue::toFormatDate($row[2]) */ $date->format('d.m.Y H:i:s') . "</td>";

                // type_name
                $type_name = App::Config()->autoinform['type_code'][$row[3]];
                if ($type_name) {
                    $type_name .= " ({$row[3]})";
                } else {
                    $type_name = $row[3];
                }
                echo "<td>{$type_name}</td>";
                // echo "<td>+7" . substr($row[1], 1) . "</td>";

                // $addnum = App::Config()->autoinform['addnum'];
                $cutnum = (int)App::Config()->autoinform['cutnum'];

                $phone = $row[1];
                // TODO: отрез и добавление символов автоинформатора
                //$phone = "+7" . substr($phone, 1);
                $phone = substr($phone, (int) $cutnum);


                echo "<td>{$phone}</td>";
                $_result_arr_test[$row[7]] = 1;
                if (App::Config()->autoinform['result'][$row[7]]) {

                    $_result = explode(";", App::Config()->autoinform['result'][$row[7]]);
                    $_result_state[$row[7]]++;
                    echo "<td>{$_result[1]}</td>";
                } else {
                    echo "<td bgcolor=#990000>{$row[7]}</td>";
                }
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
$state_sum = array();
Log::dump(array_keys($_result_arr_test), "<font color='#cc0000'>Найденые уникальных <i>result</i></font>");
Log::trace("--------------------------------");
Log::dump($_result_state, "Количество по result");
foreach ($_result_state as $key => $value) {
    $_result = explode(";", App::Config()->autoinform['result'][$key]);
    $state_sum[$_result[0]] += (int) $value;
    switch ($_result[0]) {
        case 'in_work':
            $state[0]++;
            break;
        case 'completed':
            $state[1]++;
            break;
        case 'failed':
            $state[2]++;
            break;
    }
}
Log::dump($state_sum, "Суммарный по состоянию");
Log::trace("Суммарный итог: " . $summary);
Log::trace("--------------------------------");


for ($i = 0; $i < 3; $i++)
    if ($state[$i] > 0)
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
if ($state[0] > 0) {
    $table .= '<td rowspan="' . $state[0] . '" class="head">Обработка идет</td>';

    foreach ($_result_state as $key => $value) {
        $_result = explode(";", App::Config()->autoinform['result'][$key]);
        if ($_result[0] == 'in_work') {
            $table .= "		<td align=\"center\">{$_result[1]}</td>
				<td align=\"center\">$value</td>
				<td align=\"center\">" . round(($value / $summary * 100), 2) . " %</td>
			</tr>
			<tr>";
        }
    }
    $table .= "<td align=\"center\">Итого</td>"
            . "<td align=\"center\">" . $state_sum['in_work'] . "</td>"
            . "<td align=\"center\">" . round(($state_sum['in_work']) / $summary * 100, 2) . " %</td>"
            . "</tr><tr>";
}
if ($state[1] > 0) {
    $table .= '<td rowspan="' . $state[1] . '" class="head">Обработка завершена</td>';

    foreach ($_result_state as $key => $value) {
        $_result = explode(";", App::Config()->autoinform['result'][$key]);
        if ($_result[0] == 'completed') {
            $table .= "		<td align=\"center\">{$_result[1]}</td>
				<td align=\"center\">$value</td>
				<td align=\"center\">" . round(($value / $summary * 100), 2) . " %</td>
			</tr>
			<tr>";
        }
    }
    $table .= "<td align=\"center\">Итого</td>"
            . "<td align=\"center\">" . $state_sum['completed'] . "</td>"
            . "<td align=\"center\">" . round(($state_sum['completed']) / $summary * 100, 2) . " %</td>"
            . "</tr><tr>";
}
if ($state[2] > 0) {
    $table .= '<td rowspan="' . $state[2] . '" class="head">Обработка завершена</td>';

    foreach ($_result_state as $key => $value) {
        $_result = explode(";", App::Config()->autoinform['result'][$key]);
        if ($_result[0] == 'failed') {
            $table .= "		<td align=\"center\">{$_result[1]}</td>
				<td align=\"center\">$value</td>
				<td align=\"center\">" . round(($value / $summary * 100), 2) . " %</td>
			</tr>
			<tr>";
        }
    }
    $table .= "<td align=\"center\">Итого</td>"
            . "<td align=\"center\">" . $state_sum['failed'] . "</td>"
            . "<td align=\"center\">" . round(($state_sum['failed']) / $summary * 100, 2) . " %</td>"
            . "</tr><tr>";
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
    <?php echo $table; ?>
</div>

