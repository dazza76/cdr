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
     * Time Format H:I:S
     * @param int $seconds
     * @return string
     *
     * @todo доработать
     */
    public static function time($seconds) {
        // return date('d H:i:s', $seconds - 10800);
        list($d, $h, $i, $s) = explode(' ', (date('d H i s', $seconds - 10800)));
        return (($d - 1)*24 + $h).":$i:$s";

        $seconds = (int) $seconds;
        // return $seconds;

        $h = $seconds % 3600;// / 60);
        $seconds = $second - ($h * 3600);

        $m = $seconds % 60;
        $seconds = $seconds - ($m * 60);


        return "$h:$m:$seconds";
    }

    /**
     * Sort Attributes
     * @param  string $name
     * @param  string $sort
     * @param  bool $desc
     * @return string
     */
    public static function sortable($name, $sort, $desc = false) {
        $attr = "data-column=\"{$name}\" ";
        if ($sort == $name) {
            $attr .= ($desc) ? 'data-sort="desc"' : 'data-sort="asc"';
        }

        return $attr;
    }

    /**
     * pages
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

    public static function linkUrl($file) {
        // return App::Config()->webpath . '/' . $file . '?' . App::Config()->v;

        $file = App::Config()->webpath . '/' . $file;
        if (App::Config()->v) {
            $file .= '?' . App::Config()->v;
        }
        return $file;
    }

}
