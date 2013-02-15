<?php
/**
 * Pagenator class  - Pagenator.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, CMRI
 */

/**
 * Pagenator class
 *
 * @package		AC
 */
class ACPagenator {
    public static function html($count, $offset = 0, $limit = 15) {
        $pagenator = new self($count, $offset, $limit);
        return $pagenator->render();
    }

    public $get = null;

    public $count;
    public $offset;
    public $limit;

    public $buttons = 3;
    public $buttonFirst = true;
    public $buttonLast = true;
    public $buttonNext = false;
    public $buttonPrev = false;

    public function __construct($count, $offset = 0, $limit = 15) {
        $this->count  = (int) $count;
        $this->offset = (int) $offset;
        $this->limit  = (int) $limit;
    }

    protected function _pager() {
        $count  = (int) $this->count;
        $offset = (int) $this->offset;
        $limit  = (int) $this->limit;
        $get = $_GET;
        $bc = (int) $this->buttons;

        $part_count   = ceil($count / $limit);
        $part_current = ceil($offset / $limit);

        $p = array();
        $pages = array();

        $p["pg"]     = $part_current + 1;
        $p["offset"] = $part_current * $limit;
        $p["sel"]    = "1";
        array_push($pages, $p);

        for ($i = 1; $i < $bc; $i ++ ) {
            if (($part_current + $i + 1) > $part_count)
                break;
            $p["pg"]     = $part_current + $i + 1;
            $p["offset"] = ($part_current + $i) * $limit;
            $p["sel"]    = "0";
            array_push($pages, $p);
        }

        if ($part_count - $part_current > $bc) {
            $p["pg"]     = "»";
            $p["offset"] = ($part_count - 1) * $limit;
            $p["sel"]    = "0";
            array_push($pages, $p);
        }

        for ($i = 1; $i < $bc; $i ++ ) {
            if (($part_current - $i + 1) <= 0)
                break;
            $p["pg"]     = $part_current - $i + 1;
            $p["offset"] = ($part_current - $i) * $limit;
            $p["sel"]    = "0";
            array_unshift($pages, $p);
        }

        if ($part_current > 2) {
            $p["pg"]     = "«";
            $p["offset"] = 0;
            $p["sel"]    = "0";
            array_unshift($pages, $p);
        }

        foreach ($pages as $_key => $_value) {
            $link                 = $_GET;
            $link["offset"]       = $_value["offset"];
            $pages[$_key]["link"] = http_build_query($link);
        }

        if (count($pages) < 2) {
            return array(); //"-=none=-";
        }

        return $pages;
    }

    public function render() {
        $fl         = "fl_l";
        $pages_html = "";
        $pages = $this->_pager();

        foreach ($pages as $pg) {
            $_class = "pg_lnk";
            $_title = $pg["pg"];

            if ($pg["sel"]) {
                $_class .= "_sel";
            }
            $_class .= " " . $fl;

            $pages_html .= '<a class="' . $_class . '" href="?' . $pg['link']
                    . '"><div class="pg_in">' . $_title . '</div></a>';
        }
        $pages_html = '<div class="pg_pages fl_r">' . $pages_html . '</div>';

        return $pages_html;
    }
}