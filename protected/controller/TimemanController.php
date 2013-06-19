<?php

/**
 * TimemanController class  - TimemanController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * TimemanController class
 *
 * @property ACDateTime $fromdate
 * @property ACDateTime $todate
 * @property array      $queue
 */
class TimemanController extends Controller {

    protected $_filters = array(
        'fromdate' => array('parseDatetime'),
        'todate' => array('parseDatetime'),
        'queue' => 1,
        'mob' => array('parseCheck'),
        'vip' => 1,
    );
    public $timeStep = array('0', '15', '30', '45', '60', '90', '120', '180', '32768');

    /**
     * @var CallStatus;
     */
    public $callStatus;

    public function __construct() {
        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function init($params = null) {
        parent::init($params);

        if ($this->export && $_GET['export']) {
            $this->export();
        } else {
            $this->index();
        }
    }

    /**
     * Формирет страницу
     */
    public function index() {
        $this->viewMain('page/page-timeman.php');
    }

    public function export() {
        $data = array(
            array(
                'Принято',
                $this->getComplete(0, 15),
                $this->getComplete(15, 30),
                $this->getComplete(30, 45),
                $this->getComplete(45, 60),
                $this->getComplete(60, 90),
                $this->getComplete(90, 120),
                $this->getComplete(120, 180),
                $this->getComplete(180, 32768),
                $this->getAvgComplete(),
            ),
            array(
                'Потеряно',
                $this->getAbandoned(0, 15),
                $this->getAbandoned(15, 30),
                $this->getAbandoned(30, 45),
                $this->getAbandoned(45, 60),
                $this->getAbandoned(60, 90),
                $this->getAbandoned(90, 120),
                $this->getAbandoned(120, 180),
                $this->getAbandoned(180, 32768),
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
     *
     * @param int $timemore
     * @param int $timeless
     * @return int
     */
    public function getComplete($timemore, $timeless) {
        $command = $this->_createCommand()->select('COUNT(callid) AS result')
                ->addWhere('holdtime', array($timemore, $timeless), 'BETWEEN')
                ->where(" AND status IN ('COMPLETECALLER','COMPLETEAGENT','TRANSFER')");

        $result = $command->query()->fetchAssoc();
        return (int) $result['result'];
    }

    /**
     *
     * @param int $timemore
     * @param int $timeless
     * @return int
     */
    public function getAbandoned($timemore, $timeless) {
        $command = $this->_createCommand()->select('COUNT(callid) AS result')
                ->addWhere('holdtime', array($timemore, $timeless), 'BETWEEN')
                ->where(" AND status = 'ABANDON'");

        $result = $command->query()->fetchAssoc();
        return (int) $result['result'];
    }

    /**
     * @return float
     */
    public function getAvgComplete() {
        $command = $this->_createCommand()
                ->select('AVG(holdtime)  AS result')
                ->where(" AND status IN ('COMPLETECALLER','COMPLETEAGENT','TRANSFER')");
        $result = $command->query()->fetchAssoc();
        $result = $result['result'];

        return ($result ) ? $result : 0;
    }

    /**
     * @return float
     */
    public function getAvgaBandoned() {
        $command = $this->_createCommand()
                ->select('AVG(holdtime)  AS result')
                ->where(" AND status = 'ABANDON'");
        $result = $command->query()->fetchAssoc();
        $result = $result['result'];

        return ($result ) ? $result : 0;
    }

    /**
     *
     * @return ACDbSelectCommand
     */
    protected function _createCommand() {
        $from = $this->fromdate;
        $to = $this->todate;
        $queues = ACPropertyValue::ensureNumbers($this->queue, null);
        $queues = implode(',', $queues);
        if ($queues) {
            $queues = "queue IN ({$queues})";
        }

        /* @var $command  ACDbSelectCommand */
        $command = App::Db()->createCommand()->select('1')
                ->from(CallStatus::TABLE)
                ->where($queues)
                ->addWhere('`timestamp`', array($from, $to), 'BETWEEN');


        // только мобильные
        if ($this->mob) {
            // "9ХХХХХХХХХ" и исходящие вида
            // "[9]89XXXXXXXXX".
            $command->where(" AND ("
                    . "   (LEFT(`call_status`.`callerId`, 1)='9'   AND CHAR_LENGTH(`call_status`.`callerId`)=10)"
                    . "OR (LEFT(`call_status`.`callerId`, 3)='989' AND CHAR_LENGTH(`call_status`.`callerId`)=12)"
                    . ")");
        }

        // МШЗ
        if ($this->vip) {
            $command->leftJoinOn('queue_priority', '`queue_priority`.`number`', '`call_status`.`callerId`')
                    ->select('`queue_priority`.`callerid`')
                    ->having('`queue_priority`.`callerid` IS NOT NULL');
        }

        return $command;
    }

}