<?php
/**
 * QueueAgents class  - QueueAgents.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * QueueAgents class
 *
 * @package		AC
 */
class QueueAgents extends ACDataObject {

    const TABLE = "queue_agents";

    protected static $_queueAgents;

    /**
     * Извлеч из базы список агентов
     * @return array
     */
    protected static function _init() {
        if (self::$_queueAgents) {
            return self::$_queueAgents;
        }

        $res = App::Db()->query("SELECT name, agentid FROM " . self::TABLE);
        while ($row = $res->fetchAssoc()) {
            self::$_queueAgents[$row['agentid']] = $row['name'];
        }

        return self::$_queueAgents;
    }

    /**
     * Формирует теги для поля выбора
     * @return string
     */
    public static function showOperslist() {
        $result      = "<option value=\"\" selected=\"selected\">все</option>";
        $queueAgents = self::_init();
        foreach ($queueAgents as $key => $value) {
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

        $oper = self::$_queueAgents[$id];
        if ( ! $oper) {
            $oper = 'Неизвестно';
        }
        
        return html($oper);
    }
}