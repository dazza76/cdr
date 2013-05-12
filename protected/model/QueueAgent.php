<?php

/**
 * QueueAgent class
 *
 * @property string $name        -  имя оператора
 * @property string $agentid     -  номер оператора
 * @property string $agentphone  -  телефон, на котором он СЕЙЧАС работает («0», если не в системе)
 * @property string $state       -
 * @property string $queues1     -  список очередей, в которых действует пенальти penalty1
 * @property string $penalty1    -  пенальти оператора в очередях queues1
 * @property string $queues2     -  список очередей, в которых действует пенальти penalty2
 * @property string $penalty2    -  пенальти оператора в очередях queues2
 * @property string $queues3     -  список очередей, в которых действует пенальти penalty3
 * @property string $penalty3    -  пенальти оператора в очередях queues3
 */
class QueueAgent extends ACDataObject {

    const TABLE = "queue_agents";

    protected static $_QueueAgent;

    /**
     * Извлеч из базы список агентов
     * @return array
     */
    protected static function _init() {
        if (self::$_QueueAgent) {
            return self::$_QueueAgent;
        }

        $res = App::Db()->query("SELECT name, agentid FROM " . self::TABLE . " ORDER BY name");
        while ($row = $res->fetchAssoc()) {
            self::$_QueueAgent[$row['agentid']] = $row['name'];
        }

        return self::$_QueueAgent;
    }

    /**
     * Формирует теги для поля выбора
     * @return string
     */
    public static function showOperslist() {
        $result = "<option value=\"\" selected=\"selected\">все</option>";
        $QueueAgent = self::_init();
        foreach ($QueueAgent as $key => $value) {
            $key = html($key);
            $value = html($value);
            $result .= "<option value=\"{$key}\">{$value}</option>";
        }
        return $result;
    }

    public static function getQueueAgents() {
        $QueueAgent = self::_init();
        return $QueueAgent;
    }

    /**
     * Возвращает имя оператора
     * @param int $id
     * @return string html($return)
     */
    public static function getOper($id) {
        self::_init();

        $oper = self::$_QueueAgent[$id];
        if (!$oper) {
            $oper = "Неизвестно ({$id})";
        }

        return html($oper);
    }

    public function __set($name, $value) {
        if (in_array($name, array('queues1', 'queues2', 'queues3'))) {
            $value = implode(',', ACPropertyValue::ensureFields($value));
        }
        parent::__set($name, $value);
    }

    /**
     * Список очереддей определенного номера
     * @param int $number
     * @return array
     */
    public function getQueues($number) {
        $number = (int) $number;
        $queues = "queues" . $number;
        $queues_arr = array();
        if ($this->$queues) {
            $queues_arr = explode(",", $this->$queues);
        }

        return $queues_arr;
    }

    public function getQueuesFull($title = false) {
        $queues_arr = array_merge($this->getQueues(1), $this->getQueues(2), $this->getQueues(3));
        if ($title) {
            $arr = array();
            foreach ($queues_arr as $n) {
                $queue = Queue::getQueue($n);
                if ($queue) {
                 $arr[] = $queue;
                }
            }
            $queues_arr = $arr;
        }
        // $queues = $this->getQueues(1) + $this->getQueues(2) + $this->getQueues(3);
        $queues_arr = array_unique($queues_arr);
        return $queues_arr;
    }

    /**
     * Пеналити определенного номера
     * @param int $number
     * @return array
     */
    public function getPenalty($number) {
        $number = (int) $number;
        $penalty = "penalty" . $number;
        return $this->$penalty;
    }

    public function getStatePhone() {
        switch ($this->phone) {
            case "not_in_use" : return "Свободен";
            case "used" : return "Разговаривает";
            case "ringing" : return "Звонит";
        }
    }

    public function getStateOper() {
        switch ($this->member) {
            case "online" : return "В работе";
            case "paused" : return "Перерыв";
            case "aftercall" : return "Обработка";
        }
    }

}