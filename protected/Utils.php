<?php
/**
 * Utils class  - Utils.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * Utils class
 *
 * @package		AC
 */
class Utils {

    private function __construct() {

    }

    public static function time($seconds) {
        $seconds = (int) $seconds;
        $di      = new DateInterval('PT' . $seconds . 'S');
        $di->h   = floor($seconds / 60 / 60);
        $seconds -= $di->h * 3600;
        $di->i   = floor($seconds / 60);
        $seconds -= $di->i * 60;
        $di->s   = $seconds;

        return $di->format('%H:%I:%S');
    }

    public static function functionName($param) {

    }

    public static function sortable($name, $sort, $desc) {
        $attr = "data-column=\"{$name}\"   class=\"sortable\" ";
        if ($sort == $name) {
            $attr .= ($desc) ? 'data-sort="desc"' : 'data-sort="asc"';
        }

        return $attr;
    }
}