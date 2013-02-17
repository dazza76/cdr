<?php
/**
 * QueueController class  - QueueController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
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

    protected $_sortColumn = array(
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
    );
    protected $_filters    = array(
        'fromdate' => array('_parseDatetime'),
        'todate'   => array('_parseDatetime'),
        'oper'     => 1,
        'status'   => 1,
        'queue'    => 1,
        'vip'      => 1,
        'limit'    => 1,
        'offset'   => 1,
        'sort'     => 1,
        'desc'     => 1
    );
    public $page        = "queue";
    public $chart       = "arbit";
    public $compareType = "day";
//    public $highcharts  = array();

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

    /**
     * Формирет страницу
     */
    public function index() {
        $this->chart = $this->_parseChart($this->chart);
        $chart       = 'chart' . $this->chart;
        $this->$chart();
    }

    /**
     * Очередь произвольного выбора
     */
    public function chartArbit() {
        $this->search();
        $this->getTotalResult();
        $this->content = $this->mainView('page/page-queue.php');
    }

    /**
     * Очередь - Суточный
     */
    public function chartDay() {
        $this->highcharts = $this->getDataStatisticDay();
        $this->content    = $this->mainView("page/charts/chart_{$this->chart}.php");
    }

    /**
     * Очередь - Недельный
     */
    public function chartWeek() {
        $this->highcharts = $this->getDataStatisticWeek();
        $this->content    = $this->mainView("page/charts/chart_{$this->chart}.php");
    }

    /**
     * Очередь - Месячный
     */
    public function chartMonth() {
        $this->highcharts = $this->getDataStatisticMonth();
        $this->content    = $this->mainView("page/charts/chart_{$this->chart}.php");
    }

    /**
     * Очередь - Сравнение
     */
    public function chartCompare() {
        $this->compareType = $this->_parseCompareType($this->compareType);
        ac_trace($this->compareType);
        ac_trace("from:" . $this->fromdate);
        ac_trace("to:" . $this->todate);

//        $from = $this->getDataStatisticDay($this->fromdate);
//        $to   = $this->getDataStatisticDay($this->todate);
        $act = "getDataStatistic" . $this->compareType;

        $from = $this->$act($this->fromdate);
        $to   = $this->$act($this->todate);

        $this->highcharts = array(
            'total'    => array($from[0], $from[1], $to[1]),
            'complete' => array($from[0], $from[2], $to[2])
        );

        $this->content = $this->mainView("page/charts/chart_{$this->chart}.php");
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
                ->leftJoinOn('queue_priority', 'number',
                             'SUBSTRING(' . CallStatus::TABLE . '.callerId, 3)')
                ->where("`timestamp` BETWEEN '{$this->fromdate}' AND '{$this->todate}' ")
                ->addWhere('LENGTH(' . CallStatus::TABLE . '.callerId)', 6, ">")
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
        if ($this->vip) {
            $command->having('priorityId IS NOT NULL');
        }

        $result       = $command->query();
        $this->offset = $result->calc['offset'];
        $this->limit  = $result->calc['limit'];
        $this->count  = $result->calc['count'];

        $this->rows = $result->getFetchObjects('CallStatus');
    }

    /**
     * Статистика по звонкам
     */
    public function getTotalResult() {
        $query = "
            SELECT `status`,
              COUNT(*) AS `total`,
              SUM(`holdtime`) AS `average_time`,
              SUM(`callduration`) AS `average_time_talk`
            FROM `call_status`
            WHERE
              `timestamp` BETWEEN '{$this->fromdate}' AND '{$this->todate}'
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
            GROUP BY `status`";

        $result = App::Db()->query($query);

        $this->totalResult = array(
            'total'             => 0,
            'abandoned'         => 0,
            'average_time'      => 0,
            'average_time_talk' => 0,
            'average_time_all'  => 0,
            'complete'          => 0,
            'transfered'        => 0
        );
        while ($row               = $result->fetchAssoc()) {
            switch ($row['status']) {
                case 'ABANDON':
                    $this->totalResult['abandoned']    = $row['total'];
                    $this->totalResult['average_time'] = $row['average_time'];
                    break;
                case 'COMPLETEAGENT':
                case 'COMPLETECALLER':
                    $this->totalResult['complete'] += $row['total'];
                    $this->totalResult['average_time_talk'] += $row['average_time_talk'];
                    break;
                case 'TRANSFER':
                    $this->totalResult['transfered']   = $row['total'];
                    $this->totalResult['average_time_talk'] += $row['average_time_talk'];
                    break;
            }
            $this->totalResult['total'] += $row['total'];
            $this->totalResult['average_time_all'] += $row['average_time'];
        }
    }

    /**
     * Данныйе статистики за день
     * @return array
     */
    public function getDataStatisticDay(DateTime $date = null) {
        if ($date == null) {
            $date = $this->fromdate;
        }

        $oxY = array();
        for ($i = 0; $i <= 23; $i ++ ) {
            $time = $i;

            if ($time <= 9) {
                $time = "0" . $time;
            }

            $time .= ":00";
            $oxY[$i]      = $time;
            $total[$i]    = 0;
            $complete[$i] = 0;
        }



        $query_total = "
            SELECT
                HOUR(`timestamp`) AS `hour`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              DATE(`timestamp`) = '{$date->format('Y-m-d')}'
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
            GROUP BY `hour`";
        $result      = App::Db()->query($query_total);
        while ($row         = $result->fetchAssoc()) {
            $total[$row['hour']] = (int) $row['total'];
        }

        $query_complete = "
            SELECT
              HOUR(`timestamp`) AS `hour`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              DATE(`timestamp`) = '{$date->format('Y-m-d')}'
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
            GROUP BY `hour`";

        $result = App::Db()->query($query_complete);
        while ($row    = $result->fetchAssoc()) {
            $complete[$row['hour']] = (int) $row['complete'];
        }


        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    /**
     * Данныйе статистики за неделю
     * @return array
     */
    public function getDataStatisticWeek(DateTime $date = null) {
        if ($date == null) {
            $date = $this->fromdate;
        }

        $dateTime = new DateTime($date->format('Y-m-d'));
        $n        = $date->format('N');
        $pnd      = "P{$n}D";
        $dateTime->sub(new DateInterval($pnd));

//        ac_output($dateTime);
//        ac_output($oxY);

        for ($i = 0; $i < 7; $i ++ ) {
            $dateTime->add(new DateInterval('P1D'));
            $day            = $dateTime->format('d');
            $oxY[$day]      = (int) $day;
            $total[$day]    = 0;
            $complete[$day] = 0;
        }


        $query_total = "
            SELECT
                DAY(`timestamp`) AS `date`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              WEEK(`timestamp`) = WEEK('{$date->format('Y-m-d')}')
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
            GROUP BY `date`";
        $result      = App::Db()->query($query_total);
        while ($row         = $result->fetchAssoc()) {
            $total[$row['date']] = (int) $row['total'];
        }
        ac_output($total);

        $query_complete = "
            SELECT
              DAY(`timestamp`) AS `date`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              WEEK(`timestamp`) = WEEK('{$date->format('Y-m-d')}')
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
            GROUP BY `date`";

        $result = App::Db()->query($query_complete);
        while ($row    = $result->fetchAssoc()) {
            $complete[$row['date']] = (int) $row['complete'];
        }
        ac_output($complete);

        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    /**
     * Данныйе статистики за месяц
     * @return array
     */
    public function getDataStatisticMonth(DateTime $date = null) {
        if ($date == null) {
            $date = $this->fromdate;
        }

        $t = $date->format('t');
        for ($i = 1; $i <= $t; $i ++ ) {
            $oxY[$i]      = $i;
            $total[$i]    = 0;
            $complete[$i] = 0;
        }

        $query_total = "
            SELECT
                DAY(`timestamp`) AS `day`,
                COUNT(*) AS `total`
            FROM `call_status`
            WHERE
              YEAR(`timestamp`) = '{$date->format('Y')}'
              AND  MONTH(`timestamp`) = '{$date->format('m')}'
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('ABANDON', 'COMPLETEAGENT', 'COMPLETECALLER', 'TRANSFER')
            GROUP BY `day`";
        $result      = App::Db()->query($query_total);
        while ($row         = $result->fetchAssoc()) {
            $total[$row['day']] = (int) $row['total'];
        }

        $query_complete = "
            SELECT
              DAY(`timestamp`) AS `day`,
              COUNT(*) AS `complete`
            FROM `call_status`
            WHERE
              YEAR(`timestamp`) = '{$date->format('Y')}'
              AND  MONTH(`timestamp`) = '{$date->format('m')}'
              AND  LENGTH(`callerId`) > 6
              AND `status` IN ('COMPLETEAGENT', 'COMPLETECALLER')
            GROUP BY `day`";

        $result = App::Db()->query($query_complete);
        while ($row    = $result->fetchAssoc()) {
            $complete[$row['day']] = (int) $row['complete'];
        }

        return array(array_values($oxY), array_values($total), array_values($complete));
    }

    /**
     * @param mixed $status
     * @return string
     */
    protected function _parseStatus($status) {
        switch ($status) {
            case "ABANDON":
            case "COMPLETEAGENT":
            case "COMPLETECALLER":
            case "TRANSFER":
                return $status;
                break;
        }
    }

    /**
     * @param mixed $vip
     * @return bool
     */
    protected function _parseVIP($vip) {
        return ($vip) ? true : false;
    }

    /**
     * @param mixed $chart
     * @return string
     */
    protected function _parseChart($chart) {
        switch ($chart) {
            case 'compare':
            case 'day':
            case 'month':
            case 'week': return $chart;
            case 'arbit':
            default: return 'arbit';
        }
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