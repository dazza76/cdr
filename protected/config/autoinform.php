<?php
/*
 * Автоинформатор
 *
 * charset="UTF-8"
 */

return array(
    // Файл с настройками автоинформатора.
    //
    // Путь являеться абсолютным, и должен начинаться с корня системы.
    // Windows:     Z:/var/www/autoinform.conf
    // Unix:        /var/www/autoinform.conf
    'file_conf' => 'Z:\home\localhost\html\cdr\protected\config\autoinform.conf',

    // Кол. обрз. символов
    'ordernum'=>'addnum,cutnum', // порядок действий
    'cutnum' => 1, // отрезать
    'addnum' => '+7', // добавить



    // название столбца в "дата обзвона" в таблицы "autodialout"
    'datetime' => 'datetime',
                 // 'timestamp',


    // база данных MSSQL
    'mssql'  => array(
        // отключена, используються запросы череp MySQL, ранее созданым конектом
        'enable' => 0,
        'host'   => 'localhost',
        'user'   => 'admin',
        'pass'   => '',
        'dbname' => '',
    ),
        // in_work   - Обработка идет
        // completed - Обработка завершена
        // failed    - Обработка отменена
        // none      - игнорировать
    'result' => array(
            0 => "in_work;Не обработано",
            1 => "completed;Не дослушан",
             2 => "completed;Дослушал;Дослушал/подтвердил",
             3 => "completed;Подтверждено",
             99 => "in_work;В обработке",
             98 => "in_work;Карантин",
             97 => "failed;Неудачно",
             96 => "failed;Удалено из МИС",
             95 => "none;Нет номера",
    ),

    // Если не масив или поле отсутствует, то береться из файла autoinform_callback.php
    'type' => null,
    // array(
    //     "code" => "label code",
    //     '1' => "Анализ",
    //     '2' => "Прием"
    // ),

    'type_code' => array(
        '1'=>'Страховая',
        '2'=> 'Медцентр',
        'Q01.01.050' => 'Название типа вызова 50',
        'Q01.01.049' => 'Название типа вызова 49',
        'Q01.01.048' => 'Название типа вызова 48',
        'Q01.01.046' => 'Название типа вызова 46',
        'Q01.01.042' => 'Название типа вызова 42',
    ),
);