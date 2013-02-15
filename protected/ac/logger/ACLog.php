<?php
/**
 * ACLog class  - ACLog.php file
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * Отладочный интсрументы трассировки.
 *
 * @package AC
 */
class ACLog {

    private static $_messages;
    public static $enable = false;

    public static function enable($check = null) {
        if (func_num_args() == 0) {
            return self::$enable;
        }

        self::$enable = (bool) $check;
    }

    public static function add($message, $category, $level) {
        if ( ! self::$enable) {
            return;
        }

        self::$_messages[] = array(
            'msg'   => $message,
            'ctg'   => $category,
            'level' => $level,
            'time'  => microtime(true)
        );
    }

    public static function getLogs() {
        return self::$_messages;
    }

    public static function render($print = true) {
        if ( ! self::$enable) {
            return;
        }

        $logs = self::$_messages;

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

function __log($message, $category, $level) {
    if (is_object($category)) {
        $category = get_class($category);
    }
    ACLog::add($message, $category, $level);
}

function ac_error($message, $category = 'Output') {
    __log($message, $category, 'error');
}

function ac_trace($message, $category = 'Output') {
    __log($message, $category, 'trace');
}
