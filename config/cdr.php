<?php
/*
 * Запись разговоров

 * Директория начинаеться от корня сайта (если не оговорено) со слеша  / и заканчиваеться без него.
 * Если рабочая директория являеться корнем сайта, то устанавливается пустое значение

 * charset="UTF-8"
 */

return array(
    // CDR audio file
    'monitor_dir'    => '/cdr/monitor',   // файлы записей
    'autoinform_dir' => '/cdr/monitor', // файлы записей autoinform

    'file_format'  => 'wav',            // формат записей (без точки)

    'another_base' => 0,                // если таблица cdr находиться в другой базе, то 1
    'database'     => array(
        'host'   => 'localhost',
        'user'   => 'admin',
        'pass'   => 'almazov123321',
        'dbname' => 'asterix',
    ),
);
