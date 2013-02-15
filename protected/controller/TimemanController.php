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
        'fromdate' => array('_parseDatetime'),
        'todate'   => array('_parseDatetime'),
        'queue'    => 1,
    );
    public $page     = "timeman";
    public $timeStep = array('0', '15', '30', '45', '60', '90', '120', '180', '32768');

    /**
     * @var CallStatus;
     */
    public $callStatus;

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
        $this->content = $this->mainView('page/page-timeman.php');
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
        $result  = $command->query()->fetchAssoc();
        $result  = $result['result'];

        return ($result ) ? $result : 0;
    }

    /**
     * @return float
     */
    public function getAvgaBandoned() {
        $command = $this->_createCommand()
                ->select('AVG(holdtime)  AS result')
                ->where(" AND status = 'ABANDON'");
        $result  = $command->query()->fetchAssoc();
        $result  = $result['result'];

        return ($result ) ? $result : 0;
    }

    /**
     *
     * @return ACDbSelectCommand
     */
    protected function _createCommand() {
        $from   = $this->fromdate;
        $to     = $this->todate;
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

        return $command;
    }
}