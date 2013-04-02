<?php
require_once 'protected/bootstrap.php';

$app = new Application();

//
//$fromdate = $_GET['fromdate'];
//if (!$fromdate)
//    $fromdate = date('d.m.Y H:i');
//$fromdate = new ACDateTime($fromdate);
//
//$todate = $_GET['todate'];
//if (!$todate)
//    $todate = date('d.m.Y H:i');
//$todate = new ACDateTime($todate);

$fromdate = FiltersValue::parseDatetime($_GET['fromdate']);
$todate = FiltersValue::parseDatetime($_GET['todate']);
$oper = FiltersValue::parseOper($_GET['oper']);

$select = App::Db()->createCommand()->select(array(
            "datetime",
            "agentid",
            "action",
            "agentphone",
            "NULL AS `duration`",
            "NULL AS `ringtime`",
        ))
        ->from('agent_log')
        ->addWhere("datetime", array($fromdate, $todate), 'BETWEEN');
if ($oper) {
    $select->addWhere('agentid', $oper);
}
$query = $select->toString();


$select = App::Db()->createCommand()->select(array(
            "timestamp AS datetime",
            "memberId AS agentid",
            "'incoming'",
            "queue AS agentphone",
            "callduration",
            "ringtime",
        ))
        ->from('call_status')
        ->addWhere("timestamp", array($fromdate, $todate), 'BETWEEN');
if ($oper) {
    $select->addWhere('memberId', $oper);
}
$query .= "\nUNION ALL\n" . $select->toString();


$select = App::Db()->createCommand()->select(array(
            "calldate AS datetime",
            "userfield AS agentid",
            "'outcoming'",
            "src AS agentphone",
            "duration",
            "NULL",
        ))
        ->from('cdr')
        ->order('datetime')
        ->addWhere("calldate", array($fromdate, $todate), 'BETWEEN')
        ->addWhere("length(src)", 5, "<");
if ($oper) {
    $select->addWhere('userfield', $oper);
}
$query .= "\nUNION ALL\n" . $select->toString();

$result = App::Db()->query($query);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Статистика операторов</title>

        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link href="lib/smoothness/jquery-ui.css?8" rel="stylesheet" >
        <link href="lib/smoothness/jquery-ui.dropdownchecklist.css" rel="stylesheet" >

        <link href="/cdr/css/common.css?<?php echo App::Config()->v; ?>" rel="stylesheet" >

        <script src="/cdr/js/base.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/filters-form.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/multiselect.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/fixed-header.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/grit.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/jplayer.js?<?php echo App::Config()->v; ?>"></script>


        <script type="text/javascript">
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper" >

            <div id="header" class="fixed clear_fix">
                <ul class="menu clear_fix">
                    <li>
                        <a href="#" class="header-icon icon-cdr-big"> Система анализа действий операторов </a>
                    </li>
                </ul>
            </div>

            <div id="middle" class="">

                <div class="filters clear_fix">
                    <form method="get" action="" class="of_h">
                        <input type="hidden" name="section" value="answering" />
                        <div class="filter fl_l sep">
                            <div class="label">Дата</div>
                            <div class="labeled">
                                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                                —
                                <input name="todate" type="text" autocomplete="off" value="<?php echo $todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                            </div>
                        </div>

                        <div class="filter fl_l sep">
                            <div class="label">Оператор</div>
                            <div class="labeled">
                                <select name="oper" size="1"  default="<?php echo $oper; ?>">
                                    <?php echo QueueAgent::showOperslist(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="filter fl_l">
                            <div class="labeled">
                                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="" />
                        <input type="hidden" name="desc" value="" />
                        <input type="hidden" name="offset" value="" />
                    </form>
                </div>

                <div class="filters clear_fix bigblock of_h">
                    <table class="grid" htable="1">
                        <thead>
                            <tr>
                                <th>Дата - Время</th>
                                <th>Рабочее место</th>
                                <th>Оператор</th>
                                <th>Действие</th>
                                <th>  </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="clear clear_fix">
                    <table class="grid" style="width: 900px;" htable="1">
                        <thead>
                            <tr class="b-head">
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style=""> </th>
                                <th style="width: 200px;"> </th>
                                <th style="width: 150px;"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_array()) {
                                $html_out .= '<tr>';
                                $html_out .= '<td align="center">' . $row[0] . '</td>';
                                if (($row[3] < '3000') || ($row[3] > '10000'))
                                    $html_out .= '<td align=center>' . $row[3] . '</td>';
                                else
                                    $html_out .= '<td align=center>Оч. ' . $row[3] . '</td>';
                                $html_out .= '<td>' . QueueAgent::getOper($row[1]) . '</td>';
                                switch ($row[2]) {
                                    case 'pausecall':
                                        $html_out .= '<td align=center>Поствызывная обработка</td>';
                                        if ($pausecall_begin[$row[1]] == 0)
                                            $pausecall_begin[$row[1]] = strtotime($row[0]);
                                        break;
                                    case 'unpausecal':
                                        $html_out .= "<td align=center>Обработка завершена.</td><td>Время: " . (strtotime($row[0]) - $pausecall_begin[$row[1]]) . " сек.</td>";
                                        $pausecall_length[$row[1]] += strtotime($row[0]) - $pausecall_begin[$row[1]];
                                        $pausecall_begin[$row[1]] = 0;
                                        break;
                                    case 'incoming':
                                        $html_out .= '<td align=center>Принят входящий (' . $row[3] . ')</td><td>Зв.: ' . $row[5] . ' с/Разг.: ' . $row[4] . ' с</td>';
                                        break;
                                    case 'outcoming':
                                        $html_out .= '<td align=center>Совершен исходящий</td><td>Длит.: ' . $row[4] . ' с</td>';
                                        break;
                                    case 'ready':
                                        $html_out .= '<td align=center>Готов к работе</td>';
                                        break;
                                    case 'pause':
                                        $html_out .= '<td align=center>Ушел на перерыв</td>';
                                        if ($pause_begin[$row[1]] == 0)
                                            $pause_begin[$row[1]] = strtotime($row[0]);
                                        break;
                                    case 'unpause':
                                        $html_out .= "<td align=\"center\">Вернулся с перерыва.</td><td>Время: " . (strtotime($row[0]) - $pause_begin[$row[1]]) . " сек.</td>";
                                        $pause_length[$row[1]] += strtotime($row[0]) - $pause_begin[$row[1]];
                                        $pause_begin[$row[1]] = 0;
                                        break;
                                    case 'Login':
                                        $html_out .= '<td align="center">Вошел в очередь</td>';
                                        if ($day_begin[$row[1]] == 0)
                                            $day_begin[$row[1]] = strtotime($row[0]);
                                        break;
                                    case 'Logout':
                                        $html_out .= '<td align="center">Вышел из очереди</td>';
                                        if ($day_begin != 0) {
                                            $day_length[$row[1]] = strtotime($row[0]) - $day_begin[$row[1]];
                                            $day_begin[$row[1]] = 0;
                                        };
                                        break;
                                    case 'Change':
                                        $html_out .= '<td align="center">Смена рабочего места</td>';
                                        break;
                                    case 'lost':
                                        $html_out .= '<td align="center">Потеря вызова</td>';
                                        break;
                                    case 'lostcall':
                                        $html_out .= '<td align="center">Потеря вызова</td>';
                                        break;
                                }
                            }
                            echo $html_out;
                            ?>
                        </tbody>
                    </table>
                </div>




                <div class="clear" style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>
<?php
Log::trace('Объем занимаемой памяти: ' . ACUtils::getMemoryString(ACUtils::getMemoryUsage()));
Log::trace('Запросов MySQL: ' . App::Db()->getNumQuery());
Log::trace('Время обработки MySQL: ' . sprintf(" %01.6f", App::Db()->getTimeQuery()));
Log::trace('Время работы скрипта : ' . sprintf(" %01.6f", ACUtils::getExecutionTime()));

Log::render();
