<?php

/**
 * QueueController class  - QueueController.php file
 */

/**
 * QueueController class
 *
 * @property ACDateTime $fromdate -
 * @property ACDateTime $todate   -
 * @property string     $oper -
 * @property string     $status -
 * @property string     $queue -
 * @property bool       $vip -
 * @property int        $limit        -
 * @property int        $offset       -
 * @property string     $sort
 * @property int        $desc
 */
class QueueController extends Controller {

    protected $_filters = array(
        'fromdate' => array('parseDatetime'),
        'todate' => array('parseDatetime'),
        'oper' => 1,
        'status' => 1,
        'queue' => 1,
        'vip' => 1,
        'limit' => 1,
        'offset' => 1,
        'mob' => array('parseCheck'),
        'sort' => array('parseSort', array(
                'timestamp',
                'callerId',
                'memberId',
                'callId',
                'status',
                'holdtime',
                'ringtime',
                'callduration',
                'originalPosition',
                'position',
                'queue'
            )),
        'desc' => 1
    );
    public $compareType = "day";

    /**
     * @var int
     */
    public $count;

    /**
     * @var array
     */
    public $rows = array();

    /**
     * @var array
     */
    public $totalResult;

    function __construct() {
        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function init($params = null) {
//        if ($params === null) {
//            $chart           = $this->_parseChart($_GET['chart']);
//            $params          = $_GET;
//            $params['chart'] = $chart;
//
//            if (count($params) < 2) {
//                $params = $_SESSION['pg_queue_' . $chart];
//                if ($params) {
//                    $params               = @unserialize($params);
//                }
//                $this->_sessionParams = true;
//            } else {
//                $params = $_GET;
//            }
//        }
//
//        $chart           = $this->_parseChart($_GET['chart']);
//        $params['chart'] = $chart;
//
//        $this->_filters_url             = $params;
//        $_SESSION['pg_queue_' . $chart] = @serialize($params);
//
//        Log::trace('Session parametr: ' . ((int) $this->_sessionParams));
//        Log::vardump($params);

        parent::init($params);
        $this->index();
    }

    /**
     * Формирет страницу
     */
    public function index() {
        $chart = 'chart' . $this->getSection();

        $this->dataPage['links'] .= '<script src="' . Utils::linkUrl('lib/highcharts/highcharts.js') . "\"></script>\n";

        $this->$chart();
    }

    public function export() {
        $data = array(
            array(
                'Принято',
                $this->getComplete(0, 15),
                $this->getComplete(15 + 1, 30),
                $this->getComplete(30 + 1, 45),
                $this->getComplete(45 + 1, 60),
                $this->getComplete(60 + 1, 90),
                $this->getComplete(90 + 1, 120),
                $this->getComplete(120+ 1, 180),
                $this->getComplete(180+ 1, 32768),
                $this->getAvgComplete(),
            ),
            array(
                'Потеряно',
                $this->getAbandoned(0, 15),
                $this->getAbandoned(15 + 1, 30),
                $this->getAbandoned(30 + 1, 45),
                $this->getAbandoned(45 + 1, 60),
                $this->getAbandoned(60 + 1, 90),
                $this->getAbandoned(90 + 1, 120),
                $this->getAbandoned(120+ 1, 180),
                $this->getAbandoned(180+ 1, 32768),
                $this->getAvgAbandoned(),
            )
        );

        $export = new Export($data);
        $export->thead = array(
                'Время ожидания',
                '0 - 15',
                '15 - 30',
                '30 - 45',
                '45 - 60',
                '60 - 90',
                '90 - 120',
                '120 - 180',
                '180 - +',
                'Среднее',
            );
        $export->send('timeman');
        exit();
    }


    /**
     * Очередь произвольного выбора
     */
    public function chartArbit() {
        $this->offset = FiltersValue::parseOffset($_GET['offset'], $this->limit);
        $this->search();
        $this->getTotalResult();

        if ($this->export && $_GET['export']) {
            foreach ($this->rows as $row) {
                /* @var $row CallStatus */
                if ($this->vip && ( ! $row->priorityId)) {
                    continue;
                }
                $data[] = array(
                    $row->timestamp->format('d.m.Y H:i:s'),
                    $row->getCaller(),
                    $row->dst,
                    $row->getOper(),
                    $row->getStatus(),
                    Utils::time($row->holdtime),
                    $row->ringtime,
                    Utils::time($row->callduration),
                    $row->originalPosition,
                    $row->position,
                    Queue::getQueue($row->queue),
                );

            }

            $export = new Export($data);
            $export->thead = array(
                'Дата - Время',
                'Входящий номер',
                'Назначение',
                'Оператор',
                'Действие',
                'Ожидание в очереди',
                'Поднятие трубки',
                'Длительность',
                'Вошел',
                'Вышел',
                'Очередь',
                );
            $export->send('queue');
            exit();
        }
        // LOG::dump($this->rows, 'this->rows'); // LOG::dump

        $this->viewMain('page/page-queue.php');
    }

    /**
     * Очередь - Суточный
     */
    public function chartDay() {
        $this->highcharts = $this->getDataStatisticDay($this->fromdate, $this->queue, $this->mob);

        $fromdate = $this->fromdate->format("Y-m-d");
        $todate = new DateTime($fromdate);
        $todate->add(new DateInterval("P1D"));
        $todate = $todate->format("Y-m-d");


        $this->getTotalResult($fromdate, $todate);
        $this->viewMain("page/charts/chart_{$this->getSection()}.php");
    }

    /**
     * Очередь - Недельный
     */
    public function chartWeek() {
        $this->highcharts = $this->getDataStatisticWeek($this->fromdate, $this->queue, $this->mob);
        $this->getTotalResult($this->_week_fromdate, $this->_week_todate);


        $this->viewMain("page/charts/chart_{$this->getSection()}.php");
    }

    /**
     * Очередь - Месячный
     */
    public function chartMonth() {
        $this->highcharts = $this->getDataStatisticMonth($this->fromdate, $this->queue, $this->mob);
        $this->getTotalResult($this->_month_fromdate, $this->_month_todate);

        $this->viewMain("page/charts/chart_{$this->getSection()}.php");
    }

    /**
     * Очередь - Сравнение
     */
    public function chartCompare() {
        // TODO Добавить: Таблицы сравнения
        $this->compareType = $this->_parseCompareType($this->compareType);
        Log::trace($this->compareType);
        Log::trace("from:" . $this->fromdate);
        Log::trace("to:" . $this->todate);

        $act = "getDataStatistic" . $this->compareType;

        $from = $this->$act($this->fromdate, $this->queue, $this->mob);
        $to = $this->$act($this->todate, $this->queue, $this->mob);

        $this->highcharts = array(
            'total' => array($from[0], $from[1], $to[1]),
            'complete' => array($from[0], $from[2], $to[2])
        );
        Log::dump($this, get_class($this));
        $this->viewMain("page/charts/chart_{$this->getSection()}.php");
    }

    /**
     * Поиск по заданным критериям
     */
    public function search() {
        $sort = $this->sort;
        if ($this->desc) {
            $sort .= " DESC ";
        }

        $command = App::Db()->createCommand()->select(CallStatus::TABLE . '.*')
                ->from(CallStatus::TABLE)
                ->calc()
                ->limit($this->limit)
                ->offset($this->offset)
                ->select('queue_priority.callerid AS priorityId')
                // ->leftJoinOn('cdr', 'uniqueid', "callId" )
                ->leftJoinOn('queue_priority', 'number', 'SUBSTRING(' . CallStatus::TABLE . '.callerId, 3)')
                ->where("`timestamp` BETWEEN '{$this->fromdate}' AND '{$this->todate}' ")
                ->order($sort);

        /* @var $command ACDbSelectCommand */

        if ($this->status) {
            $command->addWhere('status', $this->status);
        }
        if ($this->oper) {
            $command->addWhere('memberId', $this->oper);
        }
        if ($this->queue) {
            $command->addWhere('queue', $this->queue, 'IN');
        }


        // только мобильные
        // AND LEFT(call_status.callerId, 3)='989' AND CHAR_LENGTH(call_status.callerId)=12
        if ($this->mob) {
            // "[9]89XXXXXXXXX".
            $command->where(" AND (
                ( LEFT(`call_status`.`callerId`, 3)='989' AND CHAR_LENGTH(`call_status`.`callerId`)=12 )
             OR ( LEFT(`call_status`.`callerId`, 1)='9'   AND CHAR_LENGTH(`call_status`.`callerId`)=10 )
             ) ");
        } else {
            $command->addWhere('LENGTH(' . CallStatus::TABLE . '.callerId)', 6, ">");
        }


        if ($this->vip) {
            $command->having('priorityId IS NOT NULL');
        }

        $result = $command->query();
        // $this->offset = $result->calc['offset'];
        // $this->limit = $result->calc['limit'];
        // $this->count = $this->offset + ($this->limit * 5);// $result->calc['offset'] + $result->calc['limit'] * 5;// $result->calc['count'];

        $this->offset = $result->calc['offset'];
        $this->limit = $result->calc['limit'];
        $this->count =  $result->calc['count'];

        $this->rows = $result->getFetchObjects('CallStatus');
    }

    /**
     * Статистика по звонкам
     */
    public function getTotalResult($fromdate = null, $todate = null) {
      if($fromdate == null) {
        $fromdate = $this->fromdate;
      }
      if($todate == null) {
        $todate = $this->todate;
      }

        $command = App::Db()->createCommand()->select('`status`')
                ->select('COUNT(*) AS `total`')
                ->select('SUM(`holdtime`) AS `average_time`')
                ->select('SUM(`callduration`) AS `average_time_talk`')
                ->from('`call_status`')
                ->where("`timestamp` BETWEEN '{$fromdate}' AND '{$todate}' ")
                // ->where("AND  LENGTH(`callerId`) > 6 ")
                ->where("AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')")
                ->group('`status`');
        // статус
        if ($this->status) {
            $command->addWhere('`status`', $this->status);
        }
        // оператор
        if ($this->oper) {
            $command->addWhere('`memberId`', $this->oper);
        }
        // очереди
        if ($this->queue) {
            $command->addWhere('`queue`', $this->queue, 'IN');
        }

        // только мобильные  "[9]89XXXXXXXXX".
        if ($this->mob) {
            $command->where(" AND (
                ( LEFT(`call_status`.`callerId`, 3)='989' AND CHAR_LENGTH(`call_status`.`callerId`)=12 )
             OR ( LEFT(`call_status`.`callerId`, 1)='9'   AND CHAR_LENGTH(`call_status`.`callerId`)=10 )
             ) ");
        } else {
            $command->where('AND LENGTH(`call_status`.`callerId`) > 6');
        }


        if ($this->vip) {
            $command->from('`queue_priority`')
                    ->where(' AND `call_status`.`callerId` = `queue_priority`.`callerId` ');
            // $command->having('priorityId IS NOT NULL');
        }


        $result = $command->query();

        $this->totalResult = array(
            'total' => 0,
            'abandoned' => 0,
            'average_time' => 0,
            'average_time_talk' => 0,
            'average_time_all' => 0,
            'complete' => 0,
            'transfered' => 0
        );
        while ($row = $result->fetchAssoc()) {
            switch ($row['status']) {
                case 'ABANDON':
                    $this->totalResult['abandoned'] = $row['total'];
                    $this->totalResult['average_time'] = $row['average_time'];
                    break;
                case 'COMPLETEAGENT':
                case 'COMPLETECALLER':
                    $this->totalResult['complete'] += $row['total'];
                    $this->totalResult['average_time_talk'] += $row['average_time_talk'];
                    break;
                case 'TRANSFER':
                    $this->totalResult['transfered'] = $row['total'];
                    $this->totalResult['average_time_talk'] += $row['average_time_talk'];
                    break;
            }
            $this->totalResult['total'] += $row['total'];
            $this->totalResult['average_time_all'] += $row['average_time'];
        }
    }

    /**
     * Дневной отчет.
     * Возвращает
     *  [
     *    array oxY
     *    array total
     *    array complete
     *  ]
     * @return array
     */
    public function getDataStatisticDay(DateTime $date = null, array $query = null, $mob = false) {
        if ($date == null) {
            $date = $this->fromdate;
        }
        if ($query == null) {
            $query = $this->query;
        }
        if (count($query)) {
            $query = " AND `queue` IN ( '" . @implode("','", $query) . "' ) ";
        }
        // $mob = ($mob) ? "AND LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12" : "AND  LENGTH(`callerId`) > 6";
        $mob = ($mob) ? "AND ((LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12) OR (LEFT(`callerId`, 1)='9' AND CHAR_LENGTH(`callerId`)=10))" : "AND  LENGTH(`callerId`) > 6";

        $oxY = array();
        for ($i = 0; $i <= 23; $i++) {
            $time = $i;

            if ($time <= 9) {
                $time = "0" . $time;
            }

            $time .= ":00";
            $oxY[$i] = $time;
            $total[$i] = 0;
            $complete[$i] = 0;
        }

        $query_total = "
            SELECT
                HOUR(`timestamp`) AS `hour`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              DATE(`timestamp`) = '{$date->format('Y-m-d')}'
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
              {$query}
              {$mob}
            GROUP BY `hour`";
        $result = App::Db()->query($query_total);
        while ($row = $result->fetchAssoc()) {
            $total[$row['hour']] = (int) $row['total'];
        }

        $query_complete = "
            SELECT
              HOUR(`timestamp`) AS `hour`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              DATE(`timestamp`) = '{$date->format('Y-m-d')}'
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
              {$query}
              {$mob}
            GROUP BY `hour`";

        $result = App::Db()->query($query_complete);
        while ($row = $result->fetchAssoc()) {
            $complete[$row['hour']] = (int) $row['complete'];
        }

        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    /**
     * Недельный отчет.
     * Возвращает
     *  [
     *    array oxY
     *    array total
     *    array complete
     *  ]
     * @return array
     */
    public function getDataStatisticWeek(DateTime $date = null, array $query = null, $mob = false) {
        if ($date == null) {
            $date = $this->fromdate;
        }
        if ($query == null) {
            $query = $this->query;
        }
        if (count($query)) {
            $query = " AND queue IN ( '" . @implode("','", $query) . "' ) ";
        }
        // $mob = ($mob) ? "AND LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12" : "AND  LENGTH(`callerId`) > 6";
        $mob = ($mob) ? "AND ((LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12) OR (LEFT(`callerId`, 1)='9' AND CHAR_LENGTH(`callerId`)=10))" : "AND  LENGTH(`callerId`) > 6";

        $dateTime = new DateTime($date->format('Y-m-d'));
        $n = $date->format('N');
        $pnd = "P{$n}D";
        $dateTime->sub(new DateInterval($pnd));

        // Log::vardump($dateTime);
        // Log::vardump($oxY);

        $dayNames = array('понедельник', 'вторник', 'среда', 'четверг', 'пятница',
            'суббота', 'воскресенье');
        for ($i = 0; $i < 7; $i++) {
            $dateTime->add(new DateInterval('P1D'));
            $day = $dateTime->format('Y-m-d');
            $oxY[$day] = $dayNames[$i]; //."<br/>".$dateTime->format('m-d');
            $total[$day] = 0;
            $complete[$day] = 0;
            if ($i == 0) {
                $_fromdate = $dateTime->format('Y-m-d');
            }
            if ($i == 6) {
                $_dateTime = clone $dateTime;
                $_dateTime->add(new DateInterval('P1D'));
                $_todate = $_dateTime->format('Y-m-d');
            }
        }

        Log::dump($oxY, 'Масив по oxY (неделя ' . $dateTime->format('W') . ')');


        $this->_week_fromdate = $_fromdate;
        $this->_week_todate = $_todate;

        $query_total = "
            SELECT
                DATE(`timestamp`) AS `date`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              `timestamp` BETWEEN '{$_fromdate}' AND '{$_todate}'
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
               {$query}
               {$mob}
            GROUP BY `date`";
        $result = App::Db()->query($query_total);
        while ($row = $result->fetchAssoc()) {
            $total[$row['date']] = (int) $row['total'];
        }
        Log::dump($total, 'Масив по total');

        $query_complete = "
            SELECT
              DATE(`timestamp`) AS `date`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              `timestamp` BETWEEN '{$_fromdate}' AND '{$_todate}'
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
               {$query}
               {$mob}
            GROUP BY `date`";

        $result = App::Db()->query($query_complete);
        while ($row = $result->fetchAssoc()) {
            $complete[$row['date']] = (int) $row['complete'];
        }
        Log::dump($complete, 'Масив по complete');

        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    /**
     * Месячный отчет.
     * Возвращает
     *  [
     *    array oxY
     *    array total
     *    array complete
     *  ]
     * @return array
     */
    public function getDataStatisticMonth(DateTime $date = null, array $query = null, $mob = false) {
        if ($date == null) {
            $date = $this->fromdate;
        }
        if ($query == null) {
            $query = $this->query;
        }
        Log::dump($query, 'query');
        if (count($query)) {
            $query = " AND queue IN ( '" . @implode("','", $query) . "' ) ";
        }
        // $mob = ($mob) ? "AND LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12" : "AND  LENGTH(`callerId`) > 6";
        $mob = ($mob) ? "AND ((LEFT(`callerId`, 3)='989' AND CHAR_LENGTH(`callerId`)=12) OR (LEFT(`callerId`, 1)='9' AND CHAR_LENGTH(`callerId`)=10))" : "AND  LENGTH(`callerId`) > 6";


        $t = $date->format('t');
        for ($i = 1; $i <= $t; $i++) {
            $oxY[$i] = $i;
            $total[$i] = 0;
            $complete[$i] = 0;
        }


        $_date = $date->format('Y-m')."-01";
        $this->_month_fromdate = $_date;

        $_date = new ACDateTime($_date);
        $_date->add(new DateInterval('P1M'));
        $this->_month_todate = $_date->format('Y-m')."-01";

        $query_total = "
            SELECT
                DAY(`timestamp`) AS `day`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              `timestamp` BETWEEN '{$this->_month_fromdate}' AND '{$this->_month_todate}'
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
               {$query}
               {$mob}
            GROUP BY `day`";
        $result = App::Db()->query($query_total);
        while ($row = $result->fetchAssoc()) {
            $total[$row['day']] = (int) $row['total'];
        }


        $query_complete = "
            SELECT
              DAY(`timestamp`) AS `day`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              `timestamp` BETWEEN '{$this->_month_fromdate}' AND '{$this->_month_todate}'
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
               {$query}
               {$mob}
            GROUP BY `day`";

        $result = App::Db()->query($query_complete);
        while ($row = $result->fetchAssoc()) {
            $complete[$row['day']] = (int) $row['complete'];
        }

        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    protected function _parseCompareType($type) {
        switch ($type) {
            case 'month':
            case 'week': return $type;
            case 'day':
            default: return 'day';
        }
    }

}