<?php

/**
 * SupervisorController class  - SupervisorController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * SupervisorController class
 *
 * @package		AC
 */
class SupervisorController extends Controller {

    const STATE_MEMBER_AFTERCALL = "aftercall";
    const STATE_MEMBER_PAUSED = "paused";
    const STATE_MEMBER_ONLINE = "online";
    const STATE_PHONE_NOT_IN_USE = "not_in_use";
    const STATE_PHONE_RINGING = "ringing";
    const STATE_PHONE_USED = "used";

    protected $_filters = array(
        'fromdate' => array('parseDatetime'),
        'todate' => array('parseDatetime')
    );
    public $queues;



    function __construct() {
        App::Config('supervisor');

        parent::__construct();
    }

    public function init($params = null) {

        parent::init($params);

        if (!empty($_COOKIE['supervisor_agentid'])) {
            App::Config()->supervisor['agentid'] = explode(",", $_COOKIE['supervisor_agentid']);
        }

        Log::dump(App::Config()->supervisor, 'supervisor');

        if ($this->export && $_GET['export']) {
            $section = "section" . $this->getSection();
            $this->$section();
            $this->export();
        } else {
            $this->index();
        }
    }

    public function index() {
        $section = "section" . $this->getSection();
        $this->$section();

        if ($this->_actType === self::TYPE_ACTION) {
            $response = array(
                'queuesData' => $this->queuesData,
                'queueChart' => $this->queueChart
            );
            if ($this->queueAgents) {
                $queueAgents = array();
                foreach ($this->queueAgents as $obj) {
                    $queueAgents[] = array(
                        'agentid' => $obj->agentid,
                        //'state' => $obj->state,//sgetStatePhone(),
                        'state_phone' => $obj->getStatePhone(),
                        'phone' => $obj->phone,
                        'member' => $obj->member,
                        'queues' => implode("<br />", $obj->getQueuesFull(true))
                    );
                }
                $response['queueAgents'] = $queueAgents;
            }
            $this->content = ACJavaScript::encode(array('response' => $response));
            return;
        }

        $this->_addJsSrc('supervisor.js');
        $this->viewMain('page/supervisor/supervisor_' . $this->getSection() . '.php');
    }

    public function export() {
        $data = array();
        foreach ($this->dataAnalogue as $row) {
            $data[] = array($row['dst'], $row['count']);
        }


        $export = new Export($data);
        $export->type = $this->export;
        $export->thead = array(
            'Номер телефона',
            'Количество вызовов',
        );

        $export->send('supervisor_analogue');
        exit();
    }

    /////////////////////////////////////////////////////////////////////////////////

    /**
     * Очереди
     */
    public function sectionQueue() {
        $que_arr = Queue::getQueueArr();
        $date = new DateTime(date('Y-m-d')); // '2011-03-11 00:00:00'
        $data = $this->getStatisticQueue($date, array_keys($que_arr));


        foreach ($que_arr as $queue => $queue_name) {
            $data[$queue]['name'] = $queue_name;
            $data[$queue]['count_oper'] = 0;

            // Операторы
            $q = App::Db()->escapeString($queue);
            $result = App::Db()->createCommand()->select('COUNT(agentid)')
                    ->from('queue_agents')
                    ->where("`state` IN ('in','paused','aftercall') ")
                    ->where("AND ( POSITION('{$q}' IN queues1) AND penalty1 > 0 ")
                    ->where("OR POSITION('{$q}' IN queues2) AND penalty2 > 0 ")
                    ->where("OR POSITION('{$q}' IN queues3) AND penalty3 > 0 )")
                    ->query();
            if ($result->count()) {
                $row = $result->fetch_array(MYSQLI_NUM);

                $data[$queue]['count_oper'] = $row[0];
            }
        }

        // Log::vardump($data);

        $this->queuesData = $data;
        Log::dump($data, 'queuesData');
    }

    /**
     * Операторы
     */
    public function sectionOperator() {
        // статусы
        $states = $this->getStates();
        $this->queueChart = array(
            'ringing' => 0,
            'free' => 0,
            'used' => 0,
            'paused' => 0,
            'aftercall' => 0
        );
        // диограма
        foreach ($states as $value) {
            switch ($value['phone']) {
                case 'ringing':
                    $this->queueChart['ringing']++;
                    break;
                case 'not_in_use':
                    if ($value['member'] == 'online')
                        $this->queueChart['free']++;
                    break;
                case 'used':
                    $this->queueChart['used']++;
                    break;
            }
            switch ($value['member']) {
                case 'paused':
                    $this->queueChart['paused']++;
                    break;
                case 'aftercall':
                    $this->queueChart['aftercall']++;
                    break;
            }
        }
        Log::dump($this->queueChart, 'queueChart');


        $this->queueAgents = array();

        $result = App::Db()->createCommand()->select()
                ->from(QueueAgent::TABLE)
                ->addWhere('state', 'out', '<>')
                ->order('name')
                ->query();

        while ($queueAgent = $result->fetchObject('QueueAgent')) {
            /* @var $queueAgent QueueAgent */
            $queueAgent->phone = $states[$queueAgent->agentid]['phone'];
            $queueAgent->member = $states[$queueAgent->agentid]['member'];
            $this->queueAgents[] = $queueAgent;
        }
        Log::dump($this->queueAgents, 'queueAgents');

        // минитабличка
        $date = date('Y-m-d H:i:s', time() - 1800); //2012-07-28+03
        $date = new DateTime($date); // '2011-03-11 00:00:00'

        $this->queuesData = $this->getStatisticOperator($date);

        $this->dataPage['links'] .= '<script src="' . Utils::linkUrl('lib/highcharts/highcharts.js') . "\"></script>\n";

        // header('Refresh: 1; url='.$_SERVER['PHP_SELF'].'?section=operator');
        // connect, complete%
    }

    public function sectionAnalogue() {
        // $date           = new ACDateTime();
        // $this->fromdate = FiltersValue::parseDatetime($_GET['fromdate'], $date);
        // $this->todate   = FiltersValue::parseDatetime($_GET['todate'], $date);

        $command = App::Db()->createCommand()->select("`dst`, COUNT(*) AS `count`")
                ->from(Cdr::TABLE)
                ->addWhere("dcontext", "incoming")
                ->addWhere('`calldate`', "'{$this->fromdate}' AND '{$this->todate}'", "BETWEEN")
                ->group("`dst`");

        $channel = array();
        foreach (App::Config()->supervisor['analogue_channel'] as $value) {
            $channel[] = "`channel` LIKE '{$value}'";
        }
        $channel = implode(' OR ', $channel);
        if ($channel) {
            $command->where(" AND ({$channel})");
        }


        $result = $command->query()->getFetchAssocs();
        $this->dataAnalogue = $result;
        Log::dump($result, "dataAnalogue");
    }

    ////////////////////////////////////////////////////////////////////////////

    /**
     * Выполнения shell команды asterisk
     * @param array $params
     * @return string
     */
    public function shell($params = null) {
        if (!is_array($params)) {
            $params = array();
        }
        $params = array_replace(App::Config()->supervisor, $params);


        $queue_arr = $params['queues'];
        if (!is_array($queue_arr)) {
            $queue_arr = array_keys(Queue::getQueueArr());
        }

        if ($params['shell_exec']) {
            $shell = array();
            $shell_string = $params['shell'];
            foreach ($queue_arr as $queue) {
                $vars = array('queue' => $queue);
                $shell[] = ACUtils::parseTemplateString($shell_string, $vars);
            }
            $shell = implode(' && ', $shell);
            Log::trace($shell);
            if ($shell) {
                $result = shell_exec($shell);
            }
            return ($result) ? $result : '';
        } else {
            $result = include APPPATH . 'system/asterisk.php';
            return $result;
        }
    }

    /**
     * Статусы операторов
     * @return array
     */
    public function getStates() {
        $shell_res = $this->shell();
        Log::trace("<b>shell result::</b><pre>{$shell_res}</pre>");
        // Log::dump($shell_res, 'shell result');

        $shell_arr = explode("\n", $shell_res);
        foreach ($shell_arr as $row) {
            // TODO: Извлечение ID опертора из шел ответа
            $agentid = substr(trim($row), 0, 4);
            Log::trace('calc agentid: ' . $agentid);
            if (!is_numeric($agentid)) {
                Log::trace('---> no numeric > continue');
                continue;
            }

            if (strpos($row, "paused") !== false) {
                // Log::trace('---> paused');
                $result = App::Db()->createCommand()->select('state')
                        ->from(QueueAgent::TABLE)
                        ->addWhere('agentid', $agentid)
                        ->query()
                        ->fetchAssoc();
                if ($result['state'] == 'aftercall') {
                    // Log::trace('---> member: aftercall');
                    $member = self::STATE_MEMBER_AFTERCALL; // 'aftercall';
                } else {
                    // Log::trace('---> member: paused');
                    $member = self::STATE_MEMBER_PAUSED; // 'paused';
                }
            } else {
                // Log::trace('---> member: online');
                $member = self::STATE_MEMBER_ONLINE; // 'online';
            }

            if (strpos($row, "Not") !== false) {
                // Log::trace('---> phone: not_in_use');
                $phone = self::STATE_PHONE_NOT_IN_USE; // 'not_in_use';
            } else {
                if (strpos($row, "Ring") !== false) {
                    // Log::trace('---> phone: ringing');
                    $phone = self::STATE_PHONE_RINGING; // 'ringing';
                } else {
                    // Log::trace('---> phone: used');
                    $phone = self::STATE_PHONE_USED; // 'used';
                }
            }

            $state[$agentid]['agentid'] = $agentid;
            $state[$agentid]['member'] = $member;
            $state[$agentid]['phone'] = $phone;
        }

        return ($state) ? $state : array();
    }

    /**
     * Статистика по очередям
     * @param DateTime $datetime
     * @param array $queue
     * @return array
     */
    public function getStatisticQueue(DateTime $datetime, array $queue) {
        $servicelevel = 60;

        $datetime = $datetime->format('Y-m-d H:i:s');
        $data = array();
        foreach ($queue as $value) {
            $data[$value] = array(
                'waiting' => 0,
                'max_time' => 0,
                'served' => 0,
                'avg_call' => 0,
                'avg_hold' => 0,
                'lost' => 0,
                'avg_abandon' => 0,
                'service' => 0
            );
        }

        $result = App::Db()->createCommand()->select('COUNT(callid) AS waiting') // Ожидающих
                ->select('MIN(timestamp) AS max_time') // Дольше всего ожидает
                ->select('queue')
                ->from('ActiveCall')
                ->addWhere('queue', $queue, 'IN')
                ->addWhere('status', 'inQue')
                ->group('queue')
                ->query();
        while ($row = $result->fetchAssoc()) {
            $data[$row['queue']]['waiting'] = (int) $row['waiting'];
            $data[$row['queue']]['max_time'] = ($row['max_time']) ? (time() - strtotime($row['max_time'])) : 0;
        }

        $result = App::Db()->createCommand()
                ->select('COUNT(callid) AS served') // Обслужено
                ->select('AVG(callduration) AS avg_call') // Ср. время разговора
                ->select('AVG(holdtime) AS avg_hold') // // Ср. время ожидание
                ->select('queue')
                ->from('call_status')
                ->addWhere('queue', $queue, 'IN')
                ->addWhere('status', array('COMPLETECALLER', 'COMPLETEAGENT', 'TRANSFER'), 'IN')
                ->addWhere('timestamp', $datetime, '>=')
                ->group('queue')
                ->query();
        while ($row = $result->fetchAssoc()) {
            $data[$row['queue']]['served'] = (int) $row['served'];
            $data[$row['queue']]['avg_call'] = (string) round($row['avg_call'], 2);
            $data[$row['queue']]['avg_hold'] = (string) round($row['avg_hold'], 2);
        }

        $result = App::Db()->createCommand()
                ->select('COUNT(callid) AS `lost`') // Потеряно
                ->select('AVG(holdtime) AS avg_abandon') // Ср. ожидание потеряных
                ->select('queue')
                ->from('call_status')
                ->addWhere('queue', $queue, 'IN')
                ->addWhere('status', 'ABANDON')
                ->addWhere('timestamp', $datetime, '>=')
                ->group('queue')
                ->query();
        while ($row = $result->fetchAssoc()) {
            $data[$row['queue']]['lost'] = (int) $row['lost'];
            $data[$row['queue']]['avg_abandon'] = (string) round($row['avg_abandon'], 2);
        }


        // Service Level
        $result = App::Db()->createCommand()
                ->select('COUNT(callid) AS `service`') // SERVICE LEVEL
                ->select('queue')
                ->from('call_status')
                ->addWhere('queue', $queue, 'IN')
                ->addWhere('status', array('COMPLETECALLER', 'COMPLETEAGENT', 'TRANSFER'), 'IN')
                ->addWhere('timestamp', $datetime, '>=')
                ->addWhere('holdtime + callduration', $servicelevel, '>=')
                ->group('queue')
                ->query();
        Log::dump($result->getFetchAssocs(), 'service (SQL result)');

        while ($row = $result->fetchAssoc()) {
            // Service Level = Answered Less X seconds / Entered


            $full = $data[$row['queue']]['served'] + $data[$row['queue']]['lost'];

            $service = $row['service'];
            $service = ($full) ? (round(($service / $full), 2) * 100) : 0;

            $data[$row['queue']]['service'] = (string) $service;
        }

        return $data;
    }

    /**
     * Статистика по всем очередям
     * @param DateTime $datetime
     * @param array $queue
     * @return array
     */
    public function getStatisticOperator(DateTime $datetime) {
        $servicelevel = 60;

        $datetime = $datetime->format('Y-m-d H:i:s');
        $data = array(
            'waiting' => 0,
            'max_time' => 0,
            'served' => 0,
            'avg_call' => 0,
            'avg_hold' => 0,
            'lost' => 0,
            'avg_abandon' => 0,
            'service' => 0
        );

        $result = App::Db()->createCommand()->select('COUNT(callid) AS waiting') // Ожидающих
                        ->select('MIN(timestamp) AS max_time') // Дольше всего ожидает
                        ->from('ActiveCall')
                        ->addWhere('status', 'inQue')
                        ->query()->fetchAssoc();

        $data['waiting'] = (int) $result['waiting'];
        $data['max_time'] = (string) ($result['max_time']) ? (time() - strtotime($result['max_time'])) : 0;

        $result = App::Db()->createCommand()
                        ->select('COUNT(callid) AS served') // Обслужено
                        ->select('AVG(callduration) AS avg_call') // Ср. время разговора
                        ->select('AVG(holdtime) AS avg_hold') // // Ср. время ожидание
                        ->from('call_status')
                        ->addWhere('status', array('COMPLETECALLER', 'COMPLETEAGENT', 'TRANSFER'), 'IN')
                        ->addWhere('timestamp', $datetime, '>=')
                        ->query()->fetchAssoc();

        $data['served'] = (int) $result['served'];
        $data['avg_call'] = (string) round($result['avg_call'], 2);
        $data['avg_hold'] = (string) round($result['avg_hold'], 2);


        $result = App::Db()->createCommand()
                        ->select('COUNT(callid) AS `lost`')      // Потеряно
                        ->select('AVG(holdtime) AS avg_abandon') // Среднее время потеря
                        ->from('call_status')
                        ->addWhere('status', 'ABANDON')
                        ->addWhere('timestamp', $datetime, '>=')
                        ->query()->fetchAssoc();

        $data['lost'] = (int) $result['lost'];
        $data['avg_abandon'] = (string) round($result['avg_abandon'], 2);



        // Service Level
        $result = App::Db()->createCommand()
                        ->select('COUNT(callid) AS `service`') // SERVICE LEVEL
                        ->from('call_status')
                        ->addWhere('status', array('COMPLETECALLER', 'COMPLETEAGENT', 'TRANSFER'), 'IN')
                        ->addWhere('timestamp', $datetime, '>=')
                        ->addWhere('holdtime + callduration', $servicelevel, '>=')
                        ->query()->fetchAssoc();

        $full = $data['served'] + $data['lost'];

        $service = $row['service'];
        $service = ($full) ? (round(($service / $full), 2) * 100) : 0;

        $data['service'] = (string) $service;

        return $data;
    }

    /**
     * Парсирует шел строку
     *
     * array(
     *  0 => найденая строка
     *  1 => agentid / агент
     *  2 => queue / очередь
     *  3 => penalty / пиналити
     *  4 => (dynamic) / какой-то параметр
     *  5 => state / статус
     *  6 => calls / звонки
     *  7 => last sec / время
     * )
     *
     * @param string $row
     * @return array
     */
    private function _parseRowQueue($row) {
        // $subject = "1004 (SIP/2570) with penalty 50 (paused) (Not in use) has taken 1 calls (last was 10278 secs ago)";
        $pattern = "|"
                . "([\d]{4}) " // agentid
                . "\(SIP/([\d]{3,4})\) " // queue
                . "with penalty ([\d]+) " // penalty
                . "(\([\w ]+\))? *" // (dynamic)
                . "(\([\w\(\) ]+\))+ " // state
                . "has taken ([\d]+) calls " // calls
                . "\(last was ([\d]+) secs ago\)" // last
                . "|i";
        preg_match($pattern, $row, $matches);

        preg_match_all("|\(([\w ]+)\)|i", $matches[5], $state);
        $matches[5] = $state[1];

        return $matches;
    }

    private function _selectDbRow($query) {
        $result = App::Db()->query($query);
        if ($result->count()) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $num = $row[0];
        }
        return ($num) ? $num : 0;
    }

}