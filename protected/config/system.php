<?php
return array(
    'charset'  => 'utf-8',
    'webpath'  => '/cdr',
    // CDR
    'cdr'      => array(
        'monitor_dir' => '/cdr/monitor',
        'file_format' => 'wav',
    ),
    // база данных
    'database' => array(
        'host'   => 'localhost',
        'user'   => 'root',
        'pass'   => '',
        'dbname' => 'asterisk',
        'params' => array(
            'exception' => 0,
            'Log'       => 0,
            'charset' => 'utf8'
        )
    ),
    'debug' => 0,
    'config' => 'localhost'
);