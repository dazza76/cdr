<?php
/**
 * Queue class
 *
 * @package		AC
 */
class Queue {

    private static $_queue;

    private static function _init() {
        if ( ! self::$_queue) {
            self::$_queue = @include_once CONFIG_DIR . '/queue.php';
        }
    }

    /**
     * Возвращает список очередей, как определено в конфигурации
     * @return array
     */
    public static function getQueueArr() {
        self::_init();
        return self::$_queue;
    }

    /**
     * Формирует теги для поля выбора
     * @return string
     */
    public static function showMultiple($name, $selected) {
        self::_init();

        $options =  array(" " => "Все очереди") + self::$_queue;
        return ACHtml::select($options, $name , array("size"=>"1", "multiple"=>"multiple"), $selected);


//        $result = "<option value=\"\" >Все очереди</option>";
//        foreach (self::$_queue as $val => $str) {
//            $result .= "<option value=\"{$val}\">{$str}</option>";
//        }
//        return $result;
    }

    /**
     * Возвращает название очереди
     * @param int $id
     * @return string html($return)
     */
    public static function getQueue($id) {
        self::_init();
        return self::$_queue[$id];
    }
}