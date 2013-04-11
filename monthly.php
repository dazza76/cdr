<?php
require_once 'protected/bootstrap.php';
$maxring = 10;
$app = new Application();

$fromdate = FiltersValue::parseDatetime($_GET['fromdate']);
$oper = FiltersValue::parseOper($_GET['oper']);

//
// Выборга  "Оператор"
//
$result = App::Db()->createCommand()->select('memberId')
        ->distinct()
        ->from('call_status')
        ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE');
if ($oper) {
    $result->addWhere('memberId', $oper);
} else {
    $result->where(' AND memberId > 1');
}
$result = $result->query(); //->getFetchAssocs();

$opers = array();
while ($row = $result->fetchAssoc()) {
    $opers[$row['memberId']] = array(
        'oper' => QueueAgent::getOper($row['memberId']),
        'src' => 0,
        'dst' => 0,
        'total_call' => 0,
        'prost' => 0,
        'obrab' => 0,
        'perer' => 0,
        'maxring' => 0,
        'callduration' => 0,
        'ringtime' => 0
    );
}
$opers_list = array_keys($opers);

//
// Выборга "Входящие"
//
$result = App::Db()->createCommand()
        ->select('memberId')
        ->select('COUNT(callId) AS `count`')
        ->from('call_status')
        ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
        ->addWhere('memberId', $opers_list, 'IN')
        ->group('memberId')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['memberId']]['src'] = $row['count'];
}

//
// Выборга "Исходящие",
//         "Всего вызовов"
//
$result = App::Db()->createCommand()
        ->select('userfield')
        ->select('COUNT(uniqueid) AS `count`')
        ->from('cdr')
        ->addWhere('calldate', $fromdate->format('Y-m') . '%', 'LIKE')
        ->addWhere('userfield', $opers_list, 'IN')
        ->group('userfield')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['userfield']]['dst'] = $row['count'];
    $opers[$row['userfield']]['total_call'] = $row['count'] + $opers[$row['userfield']]['src'];
}


// Выборга "Долгое поднятие трубки"
$result = App::Db()->createCommand()
        ->select('memberId')
        ->select('COUNT(callId) AS `count`')
        ->from('call_status')
        ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
        ->addWhere('memberId', $opers_list, 'IN')
        ->addWhere('ringtime', $maxring, '>')
        ->group('memberId')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['memberId']]['maxring'] = $row['count'];
}


// Выборга "Ср. вр. разговора"
// Выборга "Ср. вр. подн. трубки";
$result = App::Db()->createCommand()
        ->select('memberId')
        ->select('AVG(callduration) AS `callduration`')
        ->select('AVG(ringtime) AS `ringtime`')
        ->from('call_status')
        ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
        ->addWhere('memberId', $opers_list, 'IN')
        ->group('memberId')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['memberId']]['callduration'] = round($row['callduration']);
    $opers[$row['memberId']]['ringtime'] = round($row['ringtime'], 1);
}



// ------------------------------------------
foreach ($opers_list as $id) {
    $from = $fromdate->format('Y-m') . "-01";
    $to = date('Y-m-d', strtotime(date('Y-m-t', strtotime($from))) + 86400);

    //
    // 1 - Простой, ЧЧ:ММ:СС
    //
    $query = "SELECT datetime, action FROM agent_log
                    WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
                    AND agentid = '{$id}' AND action IN ('Login', 'Logout')
                    ORDER BY datetime ASC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'Login':
            $start = strtotime($temprow['datetime']);
            break;
        case 'Logout':
            $start = strtotime($from);
            break;
    }
    $query = "SELECT datetime, action FROM agent_log
                    WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
                    AND agentid = '{$id}' AND action IN ('Login', 'Logout')
                    ORDER BY datetime DESC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'Logout':
            $end = strtotime($temprow['datetime']);
            break;
        case 'Login':
            $end = strtotime($to);
            break;
    }
    $total_time = $end - $start;
    $query = "SELECT datetime, action FROM agent_log
                    WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
                    AND agentid = '{$id}' AND action IN ('Login', 'Logout');";
    $tempres = @App::Db()->query($query);
    $teststring = "";
    while ($temprow = $tempres->fetchAssoc()) {
        $teststring .= $temprow['action'];
        switch ($temprow['action']) {
            case 'Login':
                $total_time -= strtotime($temprow['datetime']);
                break;
            case 'Logout':
                $total_time += strtotime($temprow['datetime']);
                break;
        }
    }
    $hours = (int) ($total_time / 3600);
    $minutes = (int) (($total_time - $hours * 3600) / 60);
    if ($minutes < 10)
        $minutes = "0$minutes";
    $seconds = ($total_time - $hours * 3600 - $minutes * 60) % 60;
    if ($seconds < 10)
        $seconds = "0$seconds";
    $total_time = "$hours:$minutes:$seconds";
    $opers[$id]['prost'] = "$total_time(" . substr_count($teststring, "LoginLogin") . "/" . substr_count($teststring, "LogoutLogout") . ")";
    // -------------------------------------------------------------
    // -------------------------------------------------------------
    // -------------------------------------------------------------
    //
    // Выборга "Обработка"
    //
    // 2---
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pause', 'unpause')
              ORDER BY datetime ASC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'pause':
            $start = strtotime($temprow['datetime']);
            break;
        case 'unpause':
            $start = strtotime($from);
            break;
    }
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pause', 'unpause')
              ORDER BY datetime DESC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'unpause':
            $end = strtotime($temprow['datetime']);
            break;
        case 'pause':
            $end = strtotime($to);
            break;
    }
    $total_time = $end - $start;
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pause', 'unpause')";
    $tempres = @App::Db()->query($query);
    $teststring = "";
    while ($temprow = $tempres->fetchAssoc()) {
        $teststring .= $temprow['action'];
        switch ($temprow['action']) {
            case 'pause':
                $total_time -= strtotime($temprow['datetime']);
                break;
            case 'unpause':
                $total_time += strtotime($temprow['datetime']);
                break;
        }
    }
    $hours = (int) ($total_time / 3600);
    $minutes = (int) (($total_time - $hours * 3600) / 60);
    if ($minutes < 10)
        $minutes = "0$minutes";
    $seconds = ($total_time - $hours * 3600 - $minutes * 60) % 60;
    if ($seconds < 10)
        $seconds = "0$seconds";
    $total_time = "$hours:$minutes:$seconds";
    $opers[$id]['obrab'] = "$total_time(" . substr_count($teststring, "pausepause") . "/" . substr_count($teststring, "unpauseunpause") . ")";
    // -------------------------------------------------------------
    // -------------------------------------------------------------
    // -------------------------------------------------------------
    //
    // Перерыв, ЧЧ:ММ:СС
    // 3--
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND action IN ('pausecall', 'unpausecal')
              ORDER BY datetime ASC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'pausecall':
            $start = strtotime($temprow['datetime']);
            break;
        case 'unpausecal':
            $start = strtotime($from);
            break;
    }
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND action IN ('pausecall', 'unpausecal')
              ORDER BY datetime DESC LIMIT 1";
    $temprow = @App::Db()->query($query)->fetchAssoc();
    switch ($temprow['action']) {
        case 'unpausecal':
            $end = strtotime($temprow['datetime']);
            break;
        case 'pausecall':
            $end = strtotime($to);
            break;
    }
    $total_time = $end - $start;
    $query = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pausecall', 'unpausecal')";
    $tempres = @App::Db()->query($query);
    $teststring = "";
    while ($temprow = $tempres->fetchAssoc()) {
        $teststring .= $temprow['action'];
        switch ($temprow['action']) {
            case 'pausecall':
                $total_time -= strtotime($temprow['datetime']);
                break;
            case 'unpausecal':
                $total_time += strtotime($temprow['datetime']);
                break;
        }
    }
    $hours = (int) ($total_time / 3600);
    $minutes = (int) (($total_time - $hours * 3600) / 60);
    if ($minutes < 10)
        $minutes = "0$minutes";
    $seconds = ($total_time - $hours * 3600 - $minutes * 60) % 60;
    if ($seconds < 10)
        $seconds = "0$seconds";
    $total_time = "$hours:$minutes:$seconds";
    $opers[$id]['perer'] = "$total_time(" . substr_count($teststring, "pausecallpausecall") . "/" . substr_count($teststring, "unpausecalunpausecal") . ")";
}
// ------------------------------------------------------------
// export
$_GET['export'] = FiltersValue::parseExport($_GET['export']);
if ($_GET['export']) {
    $export = new Export($opers);
    $export->type = $_GET['export'];
    $export->thead = array(
        'Оператор',
        'Входящие, шт.',
        'Исходящие, шт.',
        'Всего вызовов, шт.',
        'Простой, ЧЧ:ММ:СС',
        'Обработка, ЧЧ:ММ:СС',
        'Перерыв, ЧЧ:ММ:СС',
        'Долгое поднятие трубки, шт.',
        'Ср. вр. разговора, сек.',
        'Ср. вр. подн. трубки, сек.',
    );

    $export->send('monthly');
    exit();
}



Log::dump($opers, "opers");
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
        <script src="/cdr/js/datetimepicker.js?<?php echo App::Config()->v; ?>"></script>
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
                        <a href="#" class="header-icon icon-cdr-big"> Месячный отчет </a>
                    </li>
                </ul>
            </div>

            <div id="middle" class="">

                <div class="filters clear_fix">
                    <form method="get" action="" class="of_h">
                        <div class="filter fl_l sep">
                            <div class="label">Месяц</div>
                            <div class="labeled">
                                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $fromdate->format('d.m.Y'); ?>" class="datepicker" >
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
                                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
                            </div>
                        </div>


                        <div class="filter fl_l sep">
                            <div class="label">Формат экспорта</div>
                            <div class="labeled">
                                <select id="export_type" name="export">
                                    <option value="csv">CSV</option>
                                    <option value="xls">XLS</option>
                                </select>
                                <input type="submit" id="button-export" class="button" value="Экспорт" />
                            </div>
                        </div>

                    </form>
                </div>

                <div class="filters clear_fix bigblock of_h">
                    <table class="grid" htable="1">
                        <thead>
                            <tr>
                                <th>Оператор</hd>
                                <th>Входящие, шт.</hd>
                                <th>Исходящие, шт.</hd>
                                <th>Всего вызовов, шт.</hd>
                                <th>Простой, ЧЧ:ММ:СС</hd>
                                <th>Обработка, ЧЧ:ММ:СС</hd>
                                <th>Перерыв, ЧЧ:ММ:СС</hd>
                                <th>Долгое поднятие трубки, шт.</hd>
                                <th>Ср. вр. разговора, сек.</hd>
                                <th>Ср. вр. подн. трубки, сек.</hd>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="clear clear_fix">
                    <table class="grid" htable="1">
                        <thead>
                            <tr class="b-head">
                                <th style=""             > </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($opers as $row) { ?>
                                <tr>
                                    <td><?php echo $row['oper']; ?></td>
                                    <td><?php echo (int) $row['src']; ?></td>
                                    <td><?php echo (int) $row['dst']; ?></td>
                                    <td><?php echo (int) $row['src'] + (int) $row['dst']; ?></td>
                                    <td><?php echo $row['prost']; ?></td>
                                    <td><?php echo $row['obrab']; ?></td>
                                    <td><?php echo $row['perer']; ?></td>
                                    <td><?php echo (int) $row['maxring']; ?></td>
                                    <td><?php echo $row['callduration']; ?></td>
                                    <td><?php echo $row['ringtime']; ?></td>
                                </tr>
                            <?php } ?>
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


