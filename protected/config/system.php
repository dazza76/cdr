<?php
/**
 * Системные настройки.
 * Директория начинаеться от корня сайта со слеша  / и заканчиваеться без него
 *
 * charset="UTF-8"
 */
return array(
    'charset'  => 'utf-8',
    'webpath'  => '/cdr',  // корневой путь скриптов
    // CDR audio file
    'cdr'      => array(
        'monitor_dir' => '/cdr/monitor', // файлы записей
        'file_format' => 'wav',          // формат записей (без точки)
    ),
    // база данных
    'database' => array(
        'host'   => 'localhost',
        'user'   => 'root',
        'pass'   => '',
        'dbname' => 'asterisk',
        'params' => array(   // дополнительные параметры базы
            'exception' => 0,     // выбрасывать исключения
            'Log'       => 0,     // вести локальные логи запросов (большие затраты времени)
            'charset' => 'utf8'   // кодировка
        )
    ),
    // вывод логов (дебагир)
    'debug' => 0,
    // доп. файл настроек
    'config' =>'', // 'localhost'
);