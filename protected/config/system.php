<?php
/**
 * Системные настройки.
 *
 * Директория начинаеться от корня сайта со слеша  / и заканчиваеться без него.
 * Если рабочая директория являеться корнем сайта, то устанавливается пустое значение
 *
 * charset="UTF-8"
 */
return array(
    'charset'   => 'utf-8',
    'webpath'   => '/cdr', // корневой путь скриптов
    'enable_ie' => 0, // Выполнять скрипты на Internet Explorer (некорректное отображение)
    // CDR audio file
    'cdr'       => array(
        'monitor_dir'  => '/cdr/monitor', // файлы записей
        'file_format'  => 'wav', // формат записей (без точки)
        'another_base' => 0, // если таблица cdr находиться в другой базе, то 1 
        'database'     => array(
            'host'   => 'localhost',
            'user'   => 'admin',
            'pass'   => 'almazov123321',
            'dbname' => 'asterisk',
        ),
    ),
    // база данных
    'database'  => array(
        'host'   => 'localhost',
        'user'   => 'root',
        'pass'   => '',
        'dbname' => 'asterisk',
        'params' => array(// дополнительные параметры базы
            'exception' => 0, // выбрасывать исключения
            'log'       => 0, // вести локальные логи запросов (большие затраты времени)
            'charset'   => 'utf8' // кодировка
        )
    ),
    // LOG
    // вывод логов (дебагир)
    'debug'     => 0,
    // Доп. файл настроек. Переопределяет настройки из файла.
    'config'    => 'localhost'
);