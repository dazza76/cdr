<?php
/**
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */


if ( ! defined('APPPATH'))
    die('Not instanse constant APPPATH');

if (ini_get('magic_quotes_gpc') === '1')
    die('magic_quotes_gpc is ON');

// ---------------------------------------------------------------------------


function ac_dump($obj) {
    if (ini_get('xdebug.coverage_enable')) {
        var_dump($obj);
    } else {
        ACVarDumper::dump($obj, 10, true);
    }
}

function html($string, $print = false) {
    $string = ACHtml::encode($string);
    if ($print) {
        echo $string;
    } else {
        return $string;
    }
}
