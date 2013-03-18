<?php ?>
<table align="center" class="result">
    <thead>
        <tr>
            <td class="result" width="50px">ID</td>
            <td class="result" width="150px">Дата приема</td>
            <td class="result" width="200px">Тип вызова</td>
            <td class="result" width="150px">Номер телефона</td>
            <td class="result" width="150px">Результат</td>
            <td class="result" width="75px">Попытки</td>
        </tr>
    </thead>
    <tbody>
<?php
while ($row = mssql_fetch_array($res)) {
    $date     = new ACDateTime($row[2]);
    $calldate = new ACDateTime($row[4]);
    $html_out .= "<tr class=\"success\">";
    $html_out .= "<td class=\"result\" >$row[0]</td>";
    $html_out .= "<td class=\"result\" >$calldate</td>";
    switch ($row[3]) {
        case '1':
            $html_out .= "<td class=\"result\">Страховая компания</td>";
            break;
        case '2':
            $html_out .= "<td class=\"result\">Медцентр</td>";
            break;
    };

    $html_out .= "<td class=\"result\">+7" . substr($row[1], 1) . "</td>";

    switch ($row[7]) {
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
?>
    </tbody>
</table>
<br/>
<br/>



<table class="result" align="center">
    <thead class="result">
        <tr>
            <td class="result">Время</td>
            <td class="result">Результат</td>
            <td class="result">Дополнительно</td>
        </tr>
    </thead>
    <tbody class=result>
<?php
$querymy = "SELECT * FROM dialout WHERE origid='$_GET[id];'";
$res     = mysql_query($querymy);
while ($row     = mysql_fetch_array($res)) {
    switch ($row[4]) {
        case 'failed':
            $html_out .= "<tr class=\"failed\"><td class=\"result\">$row[2]</td>";
            $html_out .= "<td class=\"result\" colspan=\"2\">Безуспешно</td>";
            break;
        case 'success':
            $html_out .= "<tr class=\"success\"><td class=\"result\">$row[2]</td>";
            switch ($row[5]) {
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
                    $queue_conn  = mysql_connect("localhost", "root", "finger");
                    mysql_select_db("asterisk", $queue_conn);
                    $queue_query = "SELECT * FROM `call_status` WHERE `callId` = $row[7]";
                    $queue_res   = mysql_query($queue_query);
                    while ($queue_row   = mysql_fetch_array($queue_res)) {
                        $html_out .= "Оператор " . $queue_oper[$queue_row[2]] . "<br/>";
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
?>
    </tbody>
    <tfoot class="result">
        <tr>
            <td align="center" colspan="3">
                <input type="button" value="Закрыть это окно" onClick="window.close()" />
            </td>
        </tr>
    </tfoot>
</table>
<br/>
<?php
echo $html_out;
?>
</body>
</html>