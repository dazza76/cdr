<?php
/**
 * QueueAgent class  - QueueAgent.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * QueueAgent class
 *
 * @package		AC
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
        $result      = "<option value=\"\" selected=\"selected\">все</option>";
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
}