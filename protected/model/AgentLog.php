<?php
/**
 * AgentLog class  - AgentLog.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * AgentLog class
 *
 *
 * @property ACDateTime $datetime    - имя канала (не используется в отчете, служебная информация)
 * @property string $agentid     - то же самое.
 * @property string $agentphone  - на какой команде закончился вызов, нам не интересно.
 * @property string $action      - параметры, переданные последней команде.
 * @property string $name        - длительность вызова.
 * @property string $duration    - сколько времени вызов был отвечен
 * @property string $ringtime    - 
 * @property string $action2     - параметры, переданные последней команде.
 */
class AgentLog extends ACDataObject {

    public function __construct(array $row = null) {
        if ( $row !== null ) {
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
        }

        $this->_initAction();
    }

    public function __set($name, $value) {
        switch ($name) {
            case 'datetime':
                $value = new ACDateTime($value);
                break;
            case 'agentphone':
                if (($value < '3000') || ($value > '10000')) {
                    // $this->agentphone = $this->agentphone;
                } else {
                    $value = 'Оч. ' . $value;
                }
                break;
            default:
                # code...
                break;
        }
        if ($name == 'calldate') {
            $value = new ACDateTime($value);
        }
        parent::__set($name, $value);
    }

    private function _initAction() {
        $this->action1 = '';
        $this->action2 = '';

        switch ($this->action) {
            case 'pausecall':
                $this->action1                   = 'Поствызывная обработка';
                if ($pausecall_begin[$this->agentid] == 0)
                    $pausecall_begin[$this->agentid] = strtotime($this->datetime);
                break;
            case 'unpausecal':
                $this->action1                   = "Обработка завершена.";
                $this->action2                   = "Время: " . (strtotime($this->datetime) - $pausecall_begin[$this->agentid]) . " сек.";
                $pausecall_length[$this->agentid] += strtotime($this->datetime) - $pausecall_begin[$this->agentid];
                $pausecall_begin[$this->agentid] = 0;
                break;
            case 'incoming':
                $this->action1                   = 'Принят входящий (' . $this->agentphone . ')';
                $this->action2                   = 'Зв.: ' . $this->ringtime . ' с/Разг.: ' . $this->duration . ' с';
                break;
            case 'outcoming':
                $this->action1                   = 'Совершен исходящий';
                $this->action2                   = 'Длит.: ' . $this->duration . ' с';
                break;
            case 'ready':
                $this->action1                   = 'Готов к работе';
                break;
            case 'pause':
                $this->action1                   = 'Ушел на перерыв';
                if ($pause_begin[$this->agentid] == 0)
                    $pause_begin[$this->agentid]     = strtotime($this->datetime);
                break;
            case 'unpause':
                $this->action1                   = "Вернулся с перерыва.";
                $this->action2                   = "Время: " . (strtotime($this->datetime) - $pause_begin[$this->agentid]) . " сек.";
                $pause_length[$this->agentid] += strtotime($this->datetime) - $pause_begin[$this->agentid];
                $pause_begin[$this->agentid]     = 0;
                break;
            case 'Login':
                $this->action1                   = 'Вошел в очередь';
                if ($day_begin[$this->agentid] == 0)
                    $day_begin[$this->agentid]       = strtotime($this->datetime);
                break;
            case 'Logout':
                $this->action1                   = 'Вышел из очереди';
                if ($day_begin != 0) {
                    $day_length[$this->agentid] = strtotime($this->datetime) - $day_begin[$this->agentid];
                    $day_begin[$this->agentid]  = 0;
                };
                break;
            case 'Change':
                $this->action1 = 'Смена рабочего места';
                break;
            case 'lost':
                $this->action1 = 'Потеря вызова';
                break;
            case 'lostcall':
                $this->action1 = 'Потеря вызова';
                break;
        }
    }
}