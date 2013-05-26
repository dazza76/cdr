<?php
require_once 'protected/bootstrap.php';
$app             = new Application();
$app->controller = new AutoinformController();

if ( ! isset($_GET["id"])) {
    echo "<p align=\"center\">Security breach.<br/><a href=\"javascript:close();\">Press</a></p>";
    return;
}
?>
<html>
    <head>
        <title>
            Подробная информация о вызове <?php echo $_GET["id"]; ?>
        </title>
        <link href="/cdr/css/common.css?8" rel="stylesheet">

        <style type="text/css">

            /* table result
            ----------------------------------- */
            table.grid { border-collapse: collapse; margin-left: -1px; border-spacing: 0px; empty-cells: show; border: 1px solid #EEE; font-size: 12px; width: 100%; }
            table.grid tr { display: table-row; vertical-align: inherit; border-color: inherit; }
            table.grid td,
            table.grid th,
            table.grid thead td
            { border: 1px solid #EEE; padding: 0 4px; height: 26px; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; }
            table.grid th,
            table.grid td.head { font-size: 90%; height: 26px; color: #FFF; background-color: #8A8A8A; font-weight: bold; }
            table.grid th.sortable { cursor: pointer; }
            table.grid thead > .b-head th { height: 1px; }
            table.grid tbody tr:hover { background-color: #C6C6C6; }

            td.edit-box { padding: 0; }
            td.image-link { text-align: center; }
            td.coming-img { padding: 0px;}
            td.coming-img div { margin-left: 8px; width: 25px; height: 25px; background: url("../images/coming.png") no-repeat 0 100%;}
            td.coming-img div.coming_0 { background-position: 0px 0px; }
            td.coming-img div.coming_1 { background-position: -25px 0px; }
            td.coming-img div.coming_2 { background-position: -50px 0px; }

            tr.failed {
                border-collapse: collapse;
                background-color: #CCCCCC;
                border-bottom: 2px solid black; /* Линия снизу */
            }
            tr.success {
                border-collapse: collapse;
                background-color: #FACE8D;
                border-bottom: 2px solid black; /* Линия снизу */
            }
        </style>
    </head>
    <body>
        <?php
        $result = App::Db()->createCommand()->select()
                ->from('autodialout')
                ->addWhere('id', $_GET['id'])
                ->query();
        /* @var $row Autodialout */
        ?>
        <table align="center" class="grid">
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
                <?php while ($row    = $result->fetchObject(Autodialout)) { ?>
                    <tr class="success">
                        <td class="result" ><?php echo $row->id; ?></td>
                        <td class="result" ><?php echo $row->datetotell->format('d.m.Y H:i:s'); ?></td>
                        <td class="result"><?php echo $row->type; ?></td>
                        <td class="result"><?php echo $row->getDialnum(); ?></td>
                        <?php
                        switch ($row->result) {
                            case '99':
                                echo "<td class=\"result\">Обрабатывается</td>";
                                break;
                            case '98':
                                echo "<td class=\"result\">Карантин</td>";
                                break;
                            case '97':
                                echo "<td class=\"result\">Неудачно</td>";
                                break;
                            case '96':
                                echo "<td class=\"result\">Удалено из МИС</td>";
                                break;
                            case '95':
                                echo "<td class=\"result\">Нет номера</td>";
                                break;
                            case '0':
                                echo "<td class=\"result\">Не обрабатывался</td>";
                                break;
                            case '1':
                                echo "<td class=\"result\">Не дослушан</td>";
                                break;
                            case '2':
                                echo "<td class=\"result\">Дослушал/подтвердил</td>";
                                break;
                            case '3':
                                echo "<td class=\"result\">Направлен в КЦ</td>";
                                break;
                            case '5':
                                echo "<td class=\"result\">Направлен в КЦ";
                                break;
                            default:
                                echo "<td bgcolor=#990000 class=\"result\">{$row->result}</td>";
                                break;
                        }
                        ?>
                        <td class="result"><?php echo $row->retries; ?></td>
                    <?php } ?>
            </tbody>
        </table>
        <table class="grid" align="center">
            <thead class="result">
                <tr>
                    <td class="result">Время</td>
                    <td class="result">Номер</td>
                    <td class="result">Результат</td>
                    <td class="result">Дополнительно</td>
                </tr>
            </thead>
            <tbody class="result">
                <?php
                $result = App::Db()->createCommand()->select()
                        ->from('dialout')
                        ->addWhere('origid', $_GET['id'])
                        ->query();

                while ($row = $result->fetchObject(Autodialout)) {
                    /* @var $row Autodialout */

                    switch ($row->type) {
                        case 'failed':
                            $html_out .= "<tr class=\"failed\"><td class=\"result\">" . $row->datetime->format('d.m.Y H:i:s') . "</td>";
                            $html_out .= "<td class=\"result\" colspan=\"2\">Безуспешно</td>";
                            break;
                        case 'success':
                            $html_out .= "<tr class=\"success\"><td class=\"result\">" . $row->datetime->format('d.m.Y H:i:s') . "</td>";
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

                                    $queue_res = App::Db()->createCommand()->select()
                                            ->from('`call_status`')
                                            ->addWhere('`callId`', $row[7])
                                            ->query();

                                    while ($queue_row = $queue_res->fetch_array()) {
                                        $html_out .= "Оператор " . QueueAgent::getOper($queue_row[2]) . "<br/>";
                                        $html_out .= "Ждал $queue_row[8] сек.";
                                    };
                                    break;
                                default:
                                    $html_out .= "<td bgcolor=#990000 class=\"result\">{$row[7]}</td>";
                                    break;
                            };
                            break;
                    };
                    $html_out .= "</tr>";
                };


                echo $html_out;
                ?>
            </tbody>
            <thead class="result">
                <tr>
                    <td align="center" colspan="3">
                        <input type="button" value="Закрыть это окно" onClick="window.close();">
                    </td>
                </tr>
            </thead>
        </table>

    </body>
</html>
<?php Log::render(); ?>
