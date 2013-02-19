<?php

/**
 * Log class  - Log.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * Log class
 *
 * @package		AC
 */
class Log {

    private static $_messages;
    public static $enable = false;

    public static function trace($message, $category = 'Log', $level = 'trace') {
        if (!self::$enable) {
            return;
        }

        if (is_object($category)) {
            $category = get_class($category);
        }

        self::$_messages[] = array(
            'msg'   => $message,
            'ctg'   => $category,
            'level' => $level,
            'time'  => sprintf(" %01.6f", microtime(true) - TIME_START)
        );
    }

    public static function vardump($object) {
        ob_start();
        ac_dump($object);
        $message = ob_get_contents();
        ob_end_clean();
        self::trace($message, 'dump', 'trace');
    }

    public static function error($message, $category = 'Log') {
        self::trace($message, $category, 'error');
    }

    public static function enable($check = null) {
        if (func_num_args() == 0) {
            return self::$enable;
        }
        self::$enable = (bool) $check;
    }

    public static function getLogs() {
        return self::$_messages;
    }

    public static function render($print = true) {
        if (!self::$enable) {
            return;
        }

        $Logs = self::$_messages;

        ob_start();
        include 'view/view.php';
        $content = ob_get_contents();
        ob_end_clean();

        if ($print) {
            echo $content;
        } else {
            return $content;
        }
    }

}