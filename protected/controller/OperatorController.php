<?php
/**
 * OperatorController class  - OperatorController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * OperatorController class
 *
 * @package		AC
 */
class OperatorController extends Controller
{

    protected $_filters = array(
        'fromdate' => array('parseDatetime'), // array('_parseDatetime'),
        'todate'   => array('parseDatetime'),
        'oper'     => 1,
        'oaction'  => array('controller', 'parseOaction'),
        'limit'    => 1,
        'offset'   => 1,
    );

    /** @var mixed */
    public $dataResult;

    /** @var array логи действий по агента */
    public $agentLogs;

    /** @var array статистика агента */
    public $operLogs;

    // --------------------------------------------------------------

    function __construct()
    {
        App::Config('operator'); //->operator = @include APPPATH . 'config/operator.php';

        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function parseOaction($oaction)
    {
        if ($oaction == 1 || $oaction == 2) {
            return $oaction;
        }
    }

    public function init($params = null)
    {
        parent::init($params);

        $section = 'section' . $this->getSection();

        $this->$section();

        $this->viewMain('page/operator/' . $this->getSection() . '.php');
    }

    public function sectionOperlog()
    {
        $this->dataResult = array();
        if ( ! $this->oper) {
            return;
        }

        $this->todate->add(new DateInterval('PT23H59M59S'));
        $select = App::Db()->createCommand()->select(array(
                    "datetime",
                    "agentid",
                    "action",
                    "agentphone",
                    "NULL AS `duration`",
                    "NULL AS `ringtime`",
                ))
                ->from('agent_log')
                ->order('datetime')
                ->addWhere("datetime", array($this->fromdate, $this->todate),
                           'BETWEEN');
        if ($this->oper) {
            $select->addWhere('agentid', $this->oper);
        } else {
            $select->addWhere('agentid', 'NULL', 'IS', false);
        }
        if ($this->oaction) {
            if ($this->oaction == 1) {
                $select->addWhere('action', App::Config()->operator['calls'],
                                  'IN');
            } else {
                $select->addWhere('action', App::Config()->operator['calls'],
                                  'NOT IN');
            }
        }
        $result = $select->query();

        /* @var $day_step ACDateTime */
        $day_step = clone $this->fromdate;
        while ($day_step <= $this->todate) {
            $d                    = $day_step->format('d.m.Y');
            $this->dataResult[$d] = array(
                'logs'       => array(),
                'dey_begin'  => null,
                'dey_length' => null,
            );
            $day_step->modify('+1 day');
        }

        while ($agentLog = $result->fetchObject(AgentLog)) {
            /* @var $agentLog AgentLog */
            $date = $agentLog->datetime->format('d.m.Y');

            $this->dataResult[$date]['logs'][] = $agentLog;

            $str = "";
            $str = strtolower($agentLog->action);
            switch ($str) {
                case 'aftercall':
                    $agentLog->action1                   = 'Обработка звонка';
                    $call_begin[$agentLog->agentid]      = strtotime($agentLog->datetime);
                    break;
                case 'unaftercal':
                    $agentLog->action1                   = 'Обработка завершена';
                    $agentLog->action2                   = Utils::time(strtotime($agentLog->datetime) - $call_begin[$agentLog->agentid]);
                    $call_begin[$agentLog->agentid]      = 0;
                    break;
                // pause
                case 'pausecall':
                    $agentLog->action1                   = 'Поствызывная обработка';
                    if ($pausecall_begin[$agentLog->agentid] == 0)
                        $pausecall_begin[$agentLog->agentid] = strtotime($agentLog->datetime);
                    break;
                case 'unpausecal':
                    $agentLog->action1                   = "Обработка завершена";
                    $agentLog->action2                   = "Время: " . (strtotime($agentLog->datetime) - $pausecall_begin[$agentLog->agentid]) . " сек.";
                    $pausecall_length[$agentLog->agentid] += strtotime($agentLog->datetime) - $pausecall_begin[$agentLog->agentid];
                    $pausecall_begin[$agentLog->agentid] = 0;
                    break;

                case 'incoming':
                    $agentLog->action1               = 'Принят входящий (' . $agentLog->agentphone . ')';
                    $agentLog->action2               = 'Зв.: ' . $agentLog->ringtime . ' с/Разг.: ' . $agentLog->duration . ' с';
                    break;
                case 'outcoming':
                    $agentLog->action1               = 'Совершен исходящий';
                    $agentLog->action2               = Utils::time($agentLog->duration);
                    break;
                case 'ready':
                    $agentLog->action1               = 'Готов к работе';
                    break;
                case 'pause':
                    $agentLog->action1               = 'Ушел на перерыв';
                    if ($pause_begin[$agentLog->agentid] == 0)
                        $pause_begin[$agentLog->agentid] = strtotime($agentLog->datetime);
                    break;
                case 'unpause':
                    $agentLog->action1               = "Вернулся с перерыва";
                    $agentLog->action2               = Utils::time(
                                    strtotime($agentLog->datetime, true) - $pause_begin[$agentLog->agentid]
                    ); /* . " сек." */;
                    $pause_length[$agentLog->agentid] += strtotime($agentLog->datetime) - $pause_begin[$agentLog->agentid];
                    $pause_begin[$agentLog->agentid] = 0;
                    break;


                case 'login':
                    $agentLog->action1               = 'Вошел в очередь';
                    if ($this->dataResult[$date]['day_begin'] == 0) {
                        $this->dataResult[$date]['day_begin'] = strtotime($agentLog->datetime);
                        $this->dataResult[$date]['day_end']   = ""; //strtotime($agentLog->datetime->format('Y-m-d'). ' 23:59:59');
                    }
                    break;
                case 'logout':
                case 'logoff':
                    $agentLog->action1 = 'Вышел из очереди';
                    if ($this->dataResult[$date]['day_begin']) {
                        $this->dataResult[$date]['dey_length'] = strtotime($agentLog->datetime) - $this->dataResult[$date]['day_begin'];
                        $this->dataResult[$date]['day_end']    = $agentLog->datetime->format('H:i:s');
                    };
                    break;

                case 'change':
                    $agentLog->action1 = 'Смена рабочего места';
                    break;
                case 'lost':
                    $agentLog->action1 = 'Потеря вызова';
                    break;
                case 'lostcall':
                    $agentLog->action1 = 'Потеря вызова';
                    break;
            }
        }

        // $this->dataResult = $select->query()->getFetchObjects(AgentLog);
        Log::dump($this->dataResult, "AgentLog");
    }

    public function sectionLoad()
    {
        $dataStatus = App::Db()->createCommand()->select()
                ->from('call_status')
                ->addWhere('timestamp', array($this->fromdate, $this->todate),
                           'BETWEEN')
                ->query()
                ->getFetchObjects(CallStatus);

        $opers = array();
        /* @var $cs CallStatus */
        foreach ($dataStatus as $cs) {
            if ( ! $opers[$cs->memberId]) {
                $opers[$cs->memberId] = array(
                    'oper'   => $cs->getOper(), // Оператор
                    'total'  => 0, // Количество вызовов
                    'time'   => 0, // Время разговоров, мин
                    'avg_tr' => 0,
                    'calls'  => 0,
                );
            }
            $opers[$cs->memberId]['total'] ++;
            $opers[$cs->memberId]['time'] += $cs->callduration;
        }

        $members = array_keys($opers);
        LOG::dump($opers, "opers (original)"); // LOG::trace
        LOG::dump($members, 'members (original)'); // LOG::dump

        $k       = array_search('NONE', $members);
        if ($k !== false) {
            unset($opers[$k], $members[$k]);
        }
        unset($opers['NONE']);
        LOG::dump($opers, "opers (trim)"); // LOG::trace
        LOG::dump($members, 'members (trim)'); // LOG::dump


        $result = App::Db()->createCommand()->select('AVG(`ringtime`) AS `avg`')
                ->select('`memberId` AS `id`')
                ->from('call_status')
                ->addWhere('timestamp', array($this->fromdate, $this->todate),
                           'BETWEEN')
                ->addWhere('memberId', $members, 'IN', 'AND', true)
                ->group('memberId')
                ->query();
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['id']]['avg_tr'] = (int) $row['avg'];
        }

        $result = App::Db()->createCommand()->select('COUNT(`uniqueid`) AS `count`')
                ->select('`userfield` AS `id`')
                ->from('cdr')
                ->addWhere('calldate', array($this->fromdate, $this->todate),
                           'BETWEEN')
                ->addWhere('userfield', $members, 'IN', 'AND', true)
                ->group('userfield')
                ->query();
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['id']]['calls'] = (int) $row['count'];
        }

        $this->dataResult = $opers;
    }

    public function sectionMonthly()
    {
        $maxring  = 10;
        $fromdate = $this->fromdate;
        $oper     = $this->oper;


        // Выборга  "Оператор" ------------------------------------------------
        LOG::trace(__s('Выборга  "Оператор"')); // LOG::trace
        $result = App::Db()->createCommand()->select('memberId')
                ->distinct()
                ->from('call_status')
                ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE');
        if ($oper) {
            $result->addWhere('memberId', $oper);
        } else {
            $result->where(' AND memberId > 1');
        }
        $result = $result->query();


        $opers = array();
        while ($row   = $result->fetchAssoc()) {
            $_oper = QueueAgent::getOper($row['memberId']);
            if (strpos($_oper, 'Неизвестно') !== false) {
                continue;
            }
            $opers[$row['memberId']] = array(
                'id'           => $row['memberId'],
                'oper'         => $_oper,
                'src'          => '-',
                'dst'          => '-',
                'total_call'   => '-',
                'prost'        => '-',
                'obrab'        => '-',
                'perer'        => '-',
                'maxring'      => '-',
                'callduration' => '-',
                'ringtime'     => '-'
            );
        }
        $opers_list = array_keys($opers);
        // --------------------------------------------------------------------
        // Выборга "Входящие" -------------------------------------------------
        LOG::trace(__s("Выборга \"Входящие\"")); // LOG::trace
        $result     = App::Db()->createCommand()
                ->select('memberId')
                ->select('COUNT(callId) AS `count`')
                ->from('call_status')
                ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
                ->addWhere('memberId', $opers_list, 'IN')
                ->group('memberId')
                ->query();
        while ($row        = $result->fetchAssoc()) {
            $opers[$row['memberId']]['src'] = $row['count'];
        }
        // --------------------------------------------------------------------
        // Выборга "Исходящие", "Всего вызовов" -------------------------------
        LOG::trace(__s("Выборга \"Исходящие\", \"Всего вызовов\"")); // LOG::trace
        $result = App::Db()->createCommand()
                ->select('userfield')
                ->select('COUNT(uniqueid) AS `count`')
                ->from('cdr')
                ->addWhere('calldate', $fromdate->format('Y-m') . '%', 'LIKE')
                ->addWhere('userfield', $opers_list, 'IN')
                ->group('userfield')
                ->query();
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['userfield']]['dst']        = $row['count'];
            $opers[$row['userfield']]['total_call'] = $row['count'] + $opers[$row['userfield']]['src'];
        }
        // --------------------------------------------------------------------
        // Выборга "Долгое поднятие трубки" -----------------------------------
        LOG::trace(__s("Выборга \"Долгое поднятие трубки\"")); // LOG::trace
        $result = App::Db()->createCommand()
                ->select('memberId')
                ->select('COUNT(callId) AS `count`')
                ->from('call_status')
                ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
                ->addWhere('memberId', $opers_list, 'IN')
                ->addWhere('ringtime', $maxring, '>')
                ->group('memberId')
                ->query();
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['memberId']]['maxring'] = $row['count'];
        }
        // --------------------------------------------------------------------
        // Выборга "Ср. вр. разговора", "Ср. вр. подн. трубки" ----------------
        LOG::trace(__s("Выборга \"Ср. вр. разговора\", \"Ср. вр. подн. трубки\"")); // LOG::trace
        $result = App::Db()->createCommand()
                ->select('memberId')
                ->select('AVG(callduration) AS `callduration`')
                ->select('AVG(ringtime) AS `ringtime`')
                ->from('call_status')
                ->addWhere('timestamp', $fromdate->format('Y-m') . '%', 'LIKE')
                ->addWhere('memberId', $opers_list, 'IN')
                ->group('memberId')
                ->query();
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['memberId']]['callduration'] = round($row['callduration']);
            $opers[$row['memberId']]['ringtime']     = round($row['ringtime'], 1);
        }
        // --------------------------------------------------------------------


        $from   = $fromdate->format('Y-m') . "-01";
        $to     = date('Y-m-d',
                       strtotime(date('Y-m-t', strtotime($from))) + 86400);
        // --------------------------------------------------------------------
        // Выборга "Простой", "Обработка", "Перерыв" --------------------------
        LOG::trace(__s('Выборга "Простой", "Обработка", "Перерыв"')); // LOG::trace
        $result = App::Db()->createCommand()->select('datetime, agentid, action')
                ->from('agent_log')
                ->addWhere('datetime', $fromdate->format('Y-m') . "%", 'LIKE')
                ->addWhere('agentid', $opers_list, 'IN')
                ->addWhere('action',
                           array('Login', 'Logout', 'logoff',
                            'pause' , 'autopaused',  'unpause',
                            'pausecall', 'unpausecal',
                            'unaftercal', 'aftercall'
                    ), 'IN')
                ->query();
        // LOG::trace(__s('Result# count:').$result->count()); // LOG::trace
        // LOG::dump($result->fetch(), 'Первоя строка'); // LOG::dump
        // $result->data_seek(0);
        while ($row = $result->fetchAssoc()) {
            $action   = trim(strtolower($row['action']));
            $datetime = strtotime($row['datetime']);
            $id = $row['agentid'];

            $_test = null;

            $_prost_tmp = null;
            // LOG::trace("$id : $action : $datetime"); // LOG::trace
            switch ($action) {
                // -- простой --------------------------------------------
                case 'login':
                    // if ($opers[$id]['prost_tmp']) {
                    //     $opers[$id]['prost'] += ($datetime - $opers[$id]['prost_tmp']);
                    //     $opers[$id]['prost_tmp'] = 0;
                    // }
                    // LOG::trace("$id : $action : $datetime"); // LOG::trace
                    if (!$opers[$id]['prost_tmp']) {
                        $opers[$id]['prost_tmp'] = $datetime;
                    }
                    break;
                case 'logoff':
                case 'logout':
                    // if (!$opers[$id]['prost_tmp']) {
                    //     $opers[$id]['prost_tmp'] = $datetime;
                    // }
                    // LOG::trace("$id : $action : $datetime"); // LOG::trace
                    if ($opers[$id]['prost_tmp']) {
                        $opers[$id]['prost'] += ($datetime - $opers[$id]['prost_tmp']);
                        $opers[$id]['prost_tmp'] = 0;
                    }
                    break;

                // -- Перерыв --------------------------------------------
                case 'autopaused' :
                case 'pause':
                    if (!$opers[$id]['perer_tmp']) {
                        LOG::trace("$id : $action : $datetime"); // LOG::trace
                        $opers[$id]['perer_tmp'] = $datetime;
                    }
                    break;
                case 'unpause':
                    if ($opers[$id]['perer_tmp']) {
                        $opers[$id]['perer'] += ($datetime - $opers[$id]['perer_tmp']);
                        LOG::trace("$id : $action : $datetime - ".$opers[$id]['perer_tmp']." :: ". $opers[$id]['perer']); // LOG::trace
                        $opers[$id]['perer_tmp'] = 0;
                    }
                    break;

                // -- Обработка --------------------------------------------
                case 'aftercall':
                case 'pausecall':
                    LOG::trace("$id : $action : $datetime"); // LOG::trace
                    if (!$opers[$id]['obrab_tmp']) {
                        $opers[$id]['obrab_tmp'] = $datetime;
                    }
                    break;

                case 'unaftercal':
                case 'unpausecal':
                    LOG::trace("$id : $action : $datetime - ".$opers[$id]['obrab_tmp']." :: ". $opers[$id]['obrab']); // LOG::trace
                    if ($opers[$id]['obrab_tmp']) {
                        $opers[$id]['obrab'] += ($datetime - $opers[$id]['obrab_tmp']);
                        $opers[$id]['obrab_tmp'] = 0;
                    }
                    break;
            }
            // $opers[$id]["test_{$_test}"] .= $action;
        }
        unset($datetime, $id, $action, $_test);
        // --------------------------------------------------------------------
        // Проверка
        // foreach ($opers_list as $id) {
        //     $t = $opers[$id]['test_prost'];
        //     $opers[$id]['prost'] .= "(" . substr_count($t, "login") . "/" . substr_count($t, "logout") . ")";

        //     $t = $opers[$id]['test_obrab'];
        //     $opers[$id]['obrab'] .= "(" . substr_count($t, "pause") . "/" . substr_count($t, "unpause") . ")";

        //     $t = $opers[$id]['test_perer'];
        //     $opers[$id]['perer'] .= "(" . substr_count($t, "pausecall") . "/" . substr_count($t, "unpausecal") . ")";
        // }


        // ------------------------------------------------------------
        // export
        // $_GET['export'] = FiltersValue::parseExport($_GET['export']);
        if ($_GET['export']) {
            $export        = new Export($opers);
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
        $this->dataResult = $opers;
    }
}