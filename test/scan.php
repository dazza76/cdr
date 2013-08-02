<?php
/**
 * scan.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

define('CHARSET_UTF8', 'utf-8');
define('CHARSET_CP1251', 'windows-1251');



function _scandir($dir, $recursie = false, $basedir = null) {
    if ($basedir == null) {
        $basedir = $dir;
    }

    $scan_files = scandir($dir);
    $files = array();

    foreach ($scan_files as $file) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $filename = $dir . '/' . $file;

        if (is_dir($filename)) {
            if ($recursie) {
                $files = array_merge($files, _scandir($filename, $recursie, $basedir));
            }
            continue;
        }

        $files[] = substr($filename, strlen($basedir));
        // echo substr($filename, strlen($console->scandir)+1) . " \t";
        // echo "default: ".AC_Encoding::getEncodingFile($filename). " \t";
        // echo "set: $console->encoding ".AC_Encoding::setEncodingFile($filename, $console->encoding) . " \n";
    }
    return $files;
}

function _get_encoding_file($filename) {
        $cp_list = array(CHARSET_UTF8, CHARSET_CP1251);
        $string = file_get_contents($filename);

        foreach ($cp_list as $codepage) {
            if (md5($string) === md5(iconv($codepage, $codepage, $string))) {
                return $codepage;
            }
        }
        return null;
}

function _set_encoding_file($filename) {
        $string = file_get_contents($filename);
        $string = iconv(CHARSET_CP1251, CHARSET_UTF8, $string);
        return file_put_contents($filename, $string);
}




$dirs = array(
    __DIR__ => 0,
    __DIR__."/css" => 0,
    __DIR__."/js" => 0,
    __DIR__."/protected"=>0,
);
foreach ($dirs as $dir=>$r) {
    echo PHP_EOL;
    echo "scandir $dir ".PHP_EOL;
    $files = _scandir($dir, $r, __DIR__);
    foreach ($files as $file) {
        if (_get_encoding_file(__DIR__.'/'.$file) == CHARSET_CP1251) {
            // _set_encoding_file(__DIR__.'/'.$file);
            echo " ".$file." encoding to utf-8".PHP_EOL;
        }
    }
}



// echo _get_encoding_file(__FILE__);

// _scandir(__DIR__);
// print_r(_scandir(__DIR__));