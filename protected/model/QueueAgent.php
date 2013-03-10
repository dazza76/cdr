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

        $res = App::Db()->query("SELECT name, agentid FROM " . self::TABLE);
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
        $result     = "<option value=\"\" selected=\"selected\">все</option>";
        $QueueAgent = self::_init();
        foreach ($QueueAgent as $key => $value) {
            $key   = html($key);
            $value = html($value);
            $result .= "<option value=\"{$key}\">{$value}</option>";
        }
        return $result;
    }

    /**
     * Возвращает имя оператора
     * @param int $id
     * @return string html($return)
     */
    public static function getOper($id) {
        self::_init();

        $oper = self::$_QueueAgent[$id];
        if ( ! $oper) {
            $oper = 'Неизвестно';
        }

        return html($oper);
    }

    public function __set($name, $value) {
        if (in_array($name, array('queues1', 'queues2', 'queues3'))) {
            $value = implode(',', ACPropertyValue::ensureFields($value));
        }
        parent::__set($name, $value);
    }

    public function getQueues($number) {
        $number = (int) $number;
        $queues = "queues" . $number;
        if ( ! $this->$queues) {
            return array();
        } else {
            return explode(",", $this->$queues);
        }
    }

    public function getPenalty($number) {
        $number  = (int) $number;
        $penalty = "penalty" . $number;
        return;
        if ( ! $this->$penalty) {
            return array();
        } else {
            return explode(",", $this->$penalty);
        }
    }

    public function save() {
        if ( ! $this->agentid) {
            return null;
        }
    }

    public function edit() {

    }

    public function delete() {

    }
}