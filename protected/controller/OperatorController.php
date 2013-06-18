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
class OperatorController extends Controller {

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

    function __construct() {
        App::Config('operator'); //->operator = @include APPPATH . 'config/operator.php';

        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function parseOaction($oaction) {
        if ($oaction == 1 || $oaction == 2) {
            return $oaction;
        }
    }

    public function init($params = null) {
        parent::init($params);

        $section = 'section' . $this->getSection();

        $this->$section();

        $this->viewMain('page/operator/' . $this->getSection() . '.php');
    }

    public function sectionOperlog() {
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
            $select->addWhere('agentid', 'NULL', false);
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
        $day_step         = clone $this->fromdate;
        $this->dataResult = array();

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
            $date                              = $agentLog->datetime->format('d.m.Y');
            $this->dataResult[$date]['logs'][] = $agentLog;

            switch ($agentLog->action) {
                case 'pausecall':
                    $agentLog->action1                    = 'Поствызывная обработка';
                    if ($pausecall_begin[$agentLog->agentid] == 0)
                        $pausecall_begin[$agentLog->agentid]  = strtotime($agentLog->datetime);
                    break;
                case 'unpausecal':
                    $agentLog->action1                    = "Обработка завершена";
                    $agentLog->action2                    = "Время: " . (strtotime($agentLog->datetime) - $pausecall_begin[$agentLog->agentid]) . " сек.";
                    $pausecall_length[$agentLog->agentid] += strtotime($agentLog->datetime) - $pausecall_begin[$agentLog->agentid];
                    $pausecall_begin[$agentLog->agentid]  = 0;
                    break;
                case 'incoming':
                    $agentLog->action1                    = 'Принят входящий (' . $agentLog->agentphone . ')';
                    $agentLog->action2                    = 'Зв.: ' . $agentLog->ringtime . ' с/Разг.: ' . $agentLog->duration . ' с';
                    break;
                case 'outcoming':
                    $agentLog->action1                    = 'Совершен исходящий';
                    $agentLog->action2                    = 'Длит.: ' . $agentLog->duration . ' с';
                    break;
                case 'ready':
                    $agentLog->action1                    = 'Готов к работе';
                    break;
                case 'pause':
                    $agentLog->action1                    = 'Ушел на перерыв';
                    if ($pause_begin[$agentLog->agentid] == 0)
                        $pause_begin[$agentLog->agentid]      = strtotime($agentLog->datetime);
                    break;
                case 'unpause':
                    $agentLog->action1                    = "Вернулся с перерыва";
                    $agentLog->action2                    = "Время: " . (strtotime($agentLog->datetime) - $pause_begin[$agentLog->agentid]) . " сек.";
                    $pause_length[$agentLog->agentid] += strtotime($agentLog->datetime) - $pause_begin[$agentLog->agentid];
                    $pause_begin[$agentLog->agentid]      = 0;
                    break;
                case 'Login':
                    $agentLog->action1                    = 'Вошел в очередь';
                    if ($this->dataResult[$date]['day_begin'] == 0)
                        $this->dataResult[$date]['day_begin'] = strtotime($agentLog->datetime);
                    break;
                case 'Logout':
                case 'Logoff':
                    $agentLog->action1                    = 'Вышел из очереди';
                    if ($this->dataResult[$date]['day_begin']) {
                        $this->dataResult[$date]['dey_length'] = strtotime($agentLog->datetime) - $this->dataResult[$date]['day_begin'];
                    };
                    break;
                case 'Change':
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

    public function sectionLoad() {
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
        $k       = array_search('NONE', $members);
        if ($k !== false) {
            unset($members[$k]);
        }

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

    public function _sectionMonthly() {
        $dataResult = array();

        // $list[header] = array("Оператор","Входящие","Исходящие","Всего вызовов","Простой","Обработка","Перерыв","Разговоры","Долгое поднятие","Ср. вр. разг.","Ср. вр. подн.");
        $header_list = array(
            "oper"         => "Оператор",
            "src"          => "Входящие",
            "dst"          => "Исходящие",
            "total_call"   => "Всего вызовов",
            "prost"        => "Простой",
            "obrab"        => "Обработка",
            "perer"        => "Перерыв",
            "durations"    => "Разговоры",
            "maxring"      => "Долгое поднятие",
            "callduration" => "Ср. вр. разг.",
            "ringtime"     => "Ср. вр. подн."
        );

        $from = "{$this->year}-{$this->month}";
        if ( ! ACValidation::date($from . "-01")) {
            $from = date("Y-m");
        }

        $command = App::Db()->createCommand()->select('DISTINCT memberId')
                ->from('call_status')
                ->addWhere('timestamp', "$from%", "LIKE");
        if ($this->oper) {
            $command->addWhere('memberId', $this->oper);
        } else {
            $command->addWhere('memberId', 1, '>');
        }
        $result = $command->query(); //->getFetchAssocs();

        while ($row = $result->fetchAssoc()) {
            $dataResult[$row['memberId']] = array(
                'oper'         => QueueAgent::getOper($row['memberId']),
                'src'          => 0,
                'dst'          => 0,
                'total_call'   => 0,
                'prost'        => 0,
                'obrab'        => 0,
                'perer'        => 0,
                'maxring'      => 0,
                'callduration' => 0,
                'ringtime'     => 0
            );
        }
        $opers_list = implode(",", array_keys($dataResult));



        $query   = "SELECT memberId, COUNT(callId) FROM call_status WHERE timestamp LIKE '$from%' AND memberId IN ($opers_list) GROUP BY memberId";
        $tempres = App::Db()->query($query);
        while ($row     = $tempres->fetch_array()) {
            $dataResult[$row[0]]['src'] = $row[1];
        }

        $query   = "SELECT userfield, COUNT(uniqueid) FROM cdr WHERE calldate LIKE '$from%' AND userfield IN ($opers_list) GROUP BY userfield";
        $tempres = App::Db()->query($query);
        while ($row     = $tempres->fetch_array()) {
            $dataResult[$row[0]]['dst']        = $row[1];
            $dataResult[$row[0]]['total_call'] = $row[1] + $dataResult[$row[0]]['src'];
        }

        $query   = "SELECT agentid, datetime, action FROM agent_log WHERE datetime LIKE '$from%' AND agentid IN ($opers_list) ORDER BY datetime ASC";
        $tempres = App::Db()->query($query);
        while ($row     = $tempres->fetch_array()) {
            $dataResult[$row[0]]['action_list'][] = array(strtotime($row[1]), $row[2]);
        }
    }

    public function sectionMonthly2() {
        $list[header] = array("Оператор", "Входящие", "Исходящие", "Всего вызовов",
            "Простой", "Обработка", "Перерыв", "Разговоры", "Долгое поднятие", "Ср. вр. разг.",
            "Ср. вр. подн.");

        $output .= "<table align=center border=1>";
        $output .= "<tr>";
        $output .= "<td align=center>Оператор</td>";
        $output .= "<td align=center>Входящие<br/>шт.</td>";
        $output .= "<td align=center>Исходящие<br/>шт.</td>";
        $output .= "<td align=center>Всего вызовов<br/>шт.</td>";
        $output .= "<td align=center>Простой<br/>ЧЧ:ММ:СС</td>";
        $output .= "<td align=center>Обработка<br/>ЧЧ:ММ:СС</td>";
        $output .= "<td align=center>Перерыв<br/>ЧЧ:ММ:СС</td>";
        $output .= "<td align=center>Разговоры<br/>ЧЧ:ММ:СС</td>";
        $output .= "<td align=center>Долгое<br/>поднятие<br/>трубки, шт.</td>";
        $output .= "<td align=center>Ср. вр. разговора<br/>сек.</td>";
        $output .= "<td align=center>Ср. вр. подн. трубки<br/>сек.</td>";
        $output .= "</tr>";
        $from = "$_GET[year]-$_GET[month]-01";
        $to   = date('Y-m-d', strtotime(date('Y-m-t', strtotime($from))) + 86400);

        if ($_GET[oper] != "any")
            $query = "SELECT DISTINCT memberId FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$_GET[oper]';";
        else
            $query = "SELECT DISTINCT memberId FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId < 2000 AND memberId <> 'NONE';";
        $res   = App::Db()->query($query); // mysql_query($query) or die(mysql_error());
        while ($row   = $res->etch_array()) {
            $action_list      = "";
            $total            = 0;
            $pause            = 0;
            $aftercall        = 0;
            $output .= "<tr class=data>";
            $output .= "<td align=left>" . showoper($row[0]) . "</td>";
            $list[$row[0]][0] = iconv('utf8', 'windows-1251', showoper($row[0]));

            $tempquery        = "SELECT COUNT(callId) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]';";
            $tempres          = App::Db()->query($tempquery);
            $temprow          = $tempres->fetch_array();
            $output .= "<td align=right>" . $temprow[0] . "</td>";
            $list[$row[0]][1] = iconv('utf8', 'windows-1251',
                                      $temprow[0] . " зв.");
            $sum              = "$temprow[0]";

            $tempquery        = "SELECT COUNT(uniqueid) FROM cdr WHERE calldate >= '$from' AND calldate < '$to' AND userfield = '$row[0]';";
            $tempres          = App::Db()->query($tempquery);
            $temprow          = $tempres->fetch_array();
            $output .= "<td align=right>" . $temprow[0] . "</td>";
            $list[$row[0]][2] = iconv('utf8', 'windows-1251', "$temprow[0] зв.");
            $sum += $temprow[0];
            $list[$row[0]][3] = iconv('utf8', 'windows-1251', "$sum зв.");

            $output .= "<td align=right>" . $sum . "</td>";

            $tempquery = "SELECT datetime,action FROM agent_log WHERE datetime >= '$from' AND datetime < '$to' AND agentid = '$row[0]' ORDER BY datetime ASC;";
            $tempres   = App::Db()->query($tempquery);
            while ($temprow   = $tempres->fetch_array()) {
                $action_list .= ";$temprow[0]=>$temprow[1]";
            }
            $action_list = substr($action_list, 1);
            $action_list = explode(";", $action_list);
            foreach ($action_list as $key => $value) {
                $action_list[$key]    = explode("=>", $value);
                $action_list[$key][0] = strtotime($action_list[$key][0]);
            }
            foreach ($action_list as $key => $value) {
                if ( ! $key) {
                    if ($value[1] == "Login") {
                        $state = 'in';
                        $total -= $value[0];
                    } else {
                        $t = App::Db()->query("SELECT action FROM agent_log WHERE datetime <= '$from' AND agentid = '$row[0]' ORDER BY datetime DESC LIMIT 1;");
                        $r = $t->fetch_array();
                        switch ($r[0]) {
                            case 'Login':
                                $state = 'in';
                                $total -= strtotime($from);
                                break;
                            case 'unpause':
                                $state = 'in';
                                $total -= strtotime($from);
                                break;
                            case 'unaftercal':
                                $state = 'in';
                                $total -= strtotime($from);
                                break;
                            case 'pause':
                                $state = 'pause';
                                $pause -= strtotime($from);
                                break;
                            case 'aftercall':
                                $state = 'aftercall';
                                $aftercall -= strtotime($from);
                                break;
                        }
                        switch ($value[1]) {
                            case 'unpause':
                                if ($state == 'pause') {
                                    $state = 'in';
                                    $pause += $value[0];
                                }
                                break;
                            case 'unaftercal':
                                if ($state == 'aftercall') {
                                    $state = 'in';
                                    $aftercall += $value[0];
                                }
                                break;
                            case 'pause':
                                if ($state == 'in') {
                                    $state = 'pause';
                                    $pause -= $value[0];
                                }
                                break;
                            case 'aftercall':
                                if ($state == 'in') {
                                    $state = 'aftercall';
                                    $aftercall -= $value[0];
                                }
                                break;
                            case 'Logoff':
                                if ($state != 'out') {
                                    switch ($state) {
                                        case 'in':
                                            $total += $value[0];
                                            break;
                                        case 'pause':
                                            $pause += $value[0];
                                            break;
                                        case 'aftercall':
                                            $aftercall += $value[0];
                                            break;
                                    }
                                    $state = 'out';
                                }
                                break;
                        }
                    }
                }
                if ($key < count($action_list) - 2) {
                    switch ($value[1]) {
                        case 'Login':
                            if ($state == 'out') {
                                $state = 'in';
                                $total -= $value[0];
                            }
                            break;
                        case 'unpause':
                            if ($state == 'pause') {
                                $state = 'in';
                                $pause += $value[0];
                            }
                            break;
                        case 'unaftercal':
                            if ($state == 'aftercall') {
                                $state = 'in';
                                $aftercall += $value[0];
                            }
                            break;
                        case 'pause':
                            if ($state == 'in') {
                                $state = 'pause';
                                $pause -= $value[0];
                            }
                            break;
                        case 'aftercall':
                            if ($state == 'in') {
                                $state = 'aftercall';
                                $aftercall -= $value[0];
                            }
                            break;
                        case 'Logoff':
                            if ($state != 'out') {
                                switch ($state) {
                                    case 'in':
                                        $total += $value[0];
                                        break;
                                    case 'pause':
                                        $pause += $value[0];
                                        $total += $value[0];
                                        break;
                                    case 'aftercall':
                                        $aftercall += $value[0];
                                        $total += $value[0];
                                        break;
                                }
                                $state = 'out';
                            }
                            break;
                    }
                }
                if ($key == count($action_list) - 1) {
                    switch ($value[1]) {
                        case 'Login':
                            if ($state == 'out') {
                                $state = 'in';
                                $total += strtotime($to);
                            }
                            break;
                        case 'Logoff':
                            if ($state != 'out') {
                                switch ($state) {
                                    case 'in':
                                        $total += $value[0];
                                        break;
                                    case 'pause':
                                        $pause += $value[0];
                                        $total += $value[0];
                                        break;
                                    case 'aftercall':
                                        $aftercall += $value[0];
                                        $total += $value[0];
                                        break;
                                }
                                $state = 'out';
                            }
                            break;
                        case 'unpause':
                            if ($state == 'pause') {
                                $state = 'in';
                                $total += strtotime($to);
                            }
                            break;
                        case 'unaftercal':
                            if ($state == 'aftercall') {
                                $state = 'in';
                                $total += strtotime($to);
                            }
                            break;
                        case 'pause':
                            if ($state == 'in') {
                                $state = 'pause';
                                $pause += strtotime($to);
                            }
                            break;
                        case 'aftercall':
                            if ($state == 'in') {
                                $state = 'aftercall';
                                $aftercall += strtotime($to);
                            }
                            break;
                    }
                }
            }
            $tempquery        = "SELECT SUM(callduration) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]';";
            $tempres          = App::Db()->query($tempquery);
            $temprow          = $tempres->fetch_array();
            $temp             = $temprow[0];
            $tempquery        = "SELECT SUM(duration) FROM cdr WHERE calldate >= '$from' AND calldate < '$to' AND userfield = '$row[0]';";
            $tempres          = App::Db()->query($tempquery);
            $temprow          = $tempres->fetch_array();
            $temprow[0] += $temp;
            $output .= "<td align=center>" . get_hours($total - $aftercall - $pause) . "</td><td align=center>" . get_hours($aftercall) . "</td><td align=center>" . get_hours($pause) . "</td><td align=center>" . get_hours($temprow[0]) . "</td>";
            $list[$row[0]][4] = iconv('utf8', 'windows-1251',
                                      get_hours($total - $aftercall - $pause));
            $list[$row[0]][5] = iconv('utf8', 'windows-1251',
                                      get_hours($aftercall));
            $list[$row[0]][6] = iconv('utf8', 'windows-1251', get_hours($pause));
            $list[$row[0]][7] = iconv('utf8', 'windows-1251',
                                      get_hours($temprow[0]));
            $tempquery        = "SELECT COUNT(callId) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]' AND ringtime > '$maxring';";
            $tempres          = App::Db()->query($tempquery);
            $temprow          = $tempres->fetch_array();
            $output .= "<td align=right>" . $temprow[0] . "</td>";
            $list[$row[0]][8] = iconv('utf8', 'windows-1251', "$temprow[0] зв.");

            $tempquery         = "SELECT AVG(callduration),AVG(ringtime) FROM call_status WHERE timestamp >= '$from' AND timestamp < '$to' AND memberId = '$row[0]';";
            $tempres           = App::Db()->query($tempquery);
            $temprow           = $tempres->fetch_array();
            $output .= "<td align=right>" . round($temprow[0]) . "</td>";
            $output .= "<td align=right>" . round($temprow[1], 1) . "</td>";
            $list[$row[0]][9]  = iconv('utf8', 'windows-1251',
                                       round($temprow[0]) . " сек.");
            $list[$row[0]][10] = iconv('utf8', 'windows-1251',
                                       round($temprow[1], 1) . " сек.");
        }
    }

    public function sectionMonthly() {
        $maxring = 10;

        $fromdate = $this->fromdate;
        $oper     = $this->oper;

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
        while ($row   = $result->fetchAssoc()) {
            $opers[$row['memberId']] = array(
                'oper'         => QueueAgent::getOper($row['memberId']),
                'src'          => 0,
                'dst'          => 0,
                'total_call'   => 0,
                'prost'        => 0,
                'obrab'        => 0,
                'perer'        => 0,
                'maxring'      => 0,
                'callduration' => 0,
                'ringtime'     => 0
            );
        }
        $opers_list = array_keys($opers);
        $k          = array_search('NONE', $opers_list);
        Log::dump($k);
        if ($k !== false) {
            unset($opers_list[$k]);
        }
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
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['memberId']]['src'] = $row['count'];
        }

        //
        // Выборга "Исходящие",
        // "Всего вызовов"
        //
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
        while ($row    = $result->fetchAssoc()) {
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
        while ($row    = $result->fetchAssoc()) {
            $opers[$row['memberId']]['callduration'] = round($row['callduration']);
            $opers[$row['memberId']]['ringtime']     = round($row['ringtime'], 1);
        }



        // ------------------------------------------
        foreach ($opers_list as $id) {
            $from = $fromdate->format('Y-m') . "-01";
            $to   = date('Y-m-d',
                         strtotime(date('Y-m-t', strtotime($from))) + 86400);

            //
            // 1 - Простой, ЧЧ:ММ:СС
            //
            $query   = "SELECT datetime, action FROM agent_log
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
            $query   = "SELECT datetime, action FROM agent_log
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
            $query      = "SELECT datetime, action FROM agent_log
                    WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
                    AND agentid = '{$id}' AND action IN ('Login', 'Logout');";
            $tempres    = @App::Db()->query($query);
            $teststring = "";
            while ($temprow    = $tempres->fetchAssoc()) {
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
            $hours               = (int) ($total_time / 3600);
            $minutes             = (int) (($total_time - $hours * 3600) / 60);
            if ($minutes < 10)
                $minutes             = "0$minutes";
            $seconds             = ($total_time - $hours * 3600 - $minutes * 60) % 60;
            if ($seconds < 10)
                $seconds             = "0$seconds";
            $total_time          = "$hours:$minutes:$seconds";
            $opers[$id]['prost'] = "$total_time(" . substr_count($teststring,
                                                                 "LoginLogin") . "/" . substr_count($teststring,
                                                                                                    "LogoutLogout") . ")";
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            //
            // Выборга "Обработка"
            //
            // 2---
            $query               = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pause', 'unpause')
              ORDER BY datetime ASC LIMIT 1";
            $temprow             = @App::Db()->query($query)->fetchAssoc();
            switch ($temprow['action']) {
                case 'pause':
                    $start = strtotime($temprow['datetime']);
                    break;
                case 'unpause':
                    $start = strtotime($from);
                    break;
            }
            $query   = "SELECT datetime, action FROM agent_log
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
            $query      = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pause', 'unpause')";
            $tempres    = @App::Db()->query($query);
            $teststring = "";
            while ($temprow    = $tempres->fetchAssoc()) {
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
            $hours               = (int) ($total_time / 3600);
            $minutes             = (int) (($total_time - $hours * 3600) / 60);
            if ($minutes < 10)
                $minutes             = "0$minutes";
            $seconds             = ($total_time - $hours * 3600 - $minutes * 60) % 60;
            if ($seconds < 10)
                $seconds             = "0$seconds";
            $total_time          = "$hours:$minutes:$seconds";
            $opers[$id]['obrab'] = "$total_time(" . substr_count($teststring,
                                                                 "pausepause") . "/" . substr_count($teststring,
                                                                                                    "unpauseunpause") . ")";
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            //
            // Перерыв, ЧЧ:ММ:СС
            // 3--
            $query               = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND action IN ('pausecall', 'unpausecal')
              ORDER BY datetime ASC LIMIT 1";
            $temprow             = @App::Db()->query($query)->fetchAssoc();
            switch ($temprow['action']) {
                case 'pausecall':
                    $start = strtotime($temprow['datetime']);
                    break;
                case 'unpausecal':
                    $start = strtotime($from);
                    break;
            }
            $query   = "SELECT datetime, action FROM agent_log
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
            $query      = "SELECT datetime, action FROM agent_log
              WHERE datetime LIKE '{$fromdate->format('Y-m')}%'
              AND agentid = '{$id}' AND action IN ('pausecall', 'unpausecal')";
            $tempres    = @App::Db()->query($query);
            $teststring = "";
            while ($temprow    = $tempres->fetchAssoc()) {
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
            $hours               = (int) ($total_time / 3600);
            $minutes             = (int) (($total_time - $hours * 3600) / 60);
            if ($minutes < 10)
                $minutes             = "0$minutes";
            $seconds             = ($total_time - $hours * 3600 - $minutes * 60) % 60;
            if ($seconds < 10)
                $seconds             = "0$seconds";
            $total_time          = "$hours:$minutes:$seconds";
            $opers[$id]['perer'] = "$total_time(" . substr_count($teststring,
                                                                 "pausecallpausecall") . "/" . substr_count($teststring,
                                                                                                            "unpausecalunpausecal") . ")";
        }
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