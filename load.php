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

$dataStatus = App::Db()->createCommand()->select()
        ->from('call_status')
        ->addWhere('timestamp', array($fromdate, $todate), 'BETWEEN')
        ->query()
        ->getFetchObjects(CallStatus);

$opers = array();
/* @var $cs CallStatus */
foreach ($dataStatus as $cs) {
    if (!$opers[$cs->memberId]) {
        $opers[$cs->memberId] = array(
            'oper' => $cs->getOper(), // Оператор
            'total' => 0, // Количество вызовов
            'time' => 0, // Время разговоров, мин
            'avg_tr' => 0,
            'calls' => 0,
        );
    }
    $opers[$cs->memberId]['total']++;
    $opers[$cs->memberId]['time'] += $cs->callduration;
}

$members = array_keys($opers);
$result = App::Db()->createCommand()->select('AVG(`ringtime`) AS `avg`')
        ->select('`memberId` AS `id`')
        ->from('call_status')
        ->addWhere('timestamp', array($fromdate, $todate), 'BETWEEN')
        ->addWhere('memberId', $members, 'IN', 'AND', true)
        ->group('memberId')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['id']]['avg_tr'] = (int) $row['avg'];
}

$result = App::Db()->createCommand()->select('COUNT(`uniqueid`) AS `count`')
        ->select('`userfield` AS `id`')
        ->from('cdr')
        ->addWhere('calldate', array($fromdate, $todate), 'BETWEEN')
        ->addWhere('userfield', $members, 'IN', 'AND', true)
        ->group('userfield')
        ->query();
while ($row = $result->fetchAssoc()) {
    $opers[$row['id']]['calls'] = (int) $row['count'];
}
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
                        <a href="#" class="header-icon icon-cdr-big"> Система анализа загруженности операторов </a>
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
                                <th>Оператор</th>
                                <th>Количество вызовов</th>
                                <th>Время разговоров, мин</th>
                                <th>Ср. время разг., сек</th>
                                <th>Ср. время подн. тр., сек</th>
                                <th>Исходящих</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="clear clear_fix">
                    <table class="grid" style="width: 1000px;" htable="1">
                        <thead>
                            <tr class="b-head">
                                <th style=""             > </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($opers as $oper) { ?>
                                <tr>
                                    <td><?php echo $oper['oper']; ?></td>
                                    <td><?php echo $oper['total']; ?></td>
                                    <td><?php echo round($oper['time'] / 60, 2); ?></td>
                                    <td><?php echo round($oper['time'] / $oper['total']); ?></td>
                                    <td><?php echo $oper['avg_tr']; ?></td>
                                    <td><?php echo $oper['calls']; ?></td>
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
