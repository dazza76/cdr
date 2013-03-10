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

    /**
     * Форматированное время H:I:S
     * @param int $seconds
     * @return string
     */
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

    /**
     * Атрибуты для столбца с возможностью сортировки
     * @param  string $name
     * @param  string $sort
     * @param  bool $desc
     * @return string
     */
    public static function sortable($name, $sort, $desc = false) {
        $attr = "data-column=\"{$name}\"   class=\"sortable\" ";
        if ($sort == $name) {
            $attr .= ($desc) ? 'data-sort="desc"' : 'data-sort="asc"';
        }

        return $attr;
    }

    /**
     * Пагинатор
     * @param int $count
     * @param int $offset
     * @param int $limit
     * @param array $get
     * @return string
     */
    public static function pagenator($count, $offset = 0, $limit = 15,
                                     $get = null) {
        $pagenator = new ACPagenator($count, $offset, $limit, $get);
        return $pagenator->render();
    }
}