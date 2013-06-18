<?php
header("Content-Type: text/html; charset=UTF-8");

function is_cli() {
    return ! isset($_SERVER['HTTP_HOST']);
}



/**
 * Проверка конфигурации
 * @param bool   $boolean
 * @param string $message  сообщение о проверяемой конфигурации
 * @param string $help     информации в случии ERROR
 * @param bool   $fatal
 */
function check($boolean, $message, $help = '', $fatal = false) {
    print_tag( $boolean ? "  <ok>OK</ok>        " : sprintf("[[%s]] ", $fatal ? ' <error>ERROR</error> ' : '<warning>WARNING</warning>') );
    print_tag( sprintf("$message%s\n", $boolean ? '' : ': <error>FAILED</error>') );

    if ( ! $boolean) {
        print_tag( "            *** $help ***\n" );
        if ($fatal) {
            die("Вы должны решить эту проблему, прежде чем продолжить проверку..\n");
        }
    }
}

function check_flag($flag) {
    return ((!$flag || $flag == 'Off')) ? false : true;
}


function print_tag($thml) {
   if( is_cli() ) {
        $thml = preg_replace('/<[^>]*>/is', '', $thml) ;
   }

   echo $thml;
}

/**
 * Gets the php.ini path used by the current PHP interpretor.
 *
 * @return string the php.ini path
 */
function get_ini_path() {
    if ($path = get_cfg_var('cfg_file_path')) {
        return $path;
    }
    return 'WARNING: не используется файл php.ini';
}

if ( ! is_cli()) {
    echo '<html>
    <head>
    <style>
        ok { color: rgb(54, 162, 54); font-weight: bold; }
        warning { color: rgb(167, 167, 39); font-weight: bold; }
        error { color: rgb(255, 0, 0); font-weight: bold; }
    </style>
    </head>
    <body>

    <pre>'.PHP_EOL;
}


echo "********************************\n";
echo "*                              *\n";
echo "*  Проверка конфигурации       *\n";
echo "*                              *\n";
echo "********************************\n\n";

echo sprintf("Конфигурация PHP: %s\n\n", get_ini_path());
if (is_cli()) {
    echo "** WARNING **\n";
    echo "*     PHP запущен как CLI скрипт, и он может использовать другой файл php.ini\n";
    echo "*     чем тот, который используеться на веб-сервере.\n";
    echo "*     Запустите эту с веб-сервера.\n";
    echo "** WARNING **\n";
}


// mandatory
echo "\n** Обязательные требования **\n\n";

check(version_compare(phpversion(), '5.3.1', '>='), sprintf('Минималья версия PHP 5.3.1, текущая версия %s', phpversion()), 'Обновите PHP', true);
check(function_exists('iconv'),          'Функция iconv() доступна', 'Установить и включить расширение iconv', true);
check(function_exists('utf8_decode'),    'Функция utf8_decode() доступна', 'Установить и включить расширение XML', true);

check(!check_flag(ini_get('magic_quotes_gpc')),    sprintf("php.ini параметр <b>magic_quotes_gpc</b> установлен в Off (%s)", ini_get('magic_quotes_gpc') ), "Установите его в 'Off' в php.ini", true);
check(!check_flag(ini_get('register_globals')),    sprintf("php.ini параметр <b>register_globals</b> установлен в Off (%s)", ini_get('register_globals') ), "Установите его в 'Off' в php.ini", true);

// warnings
echo "\n** Дополнительные проверки **\n\n";
// check( !check_flag(ini_get('session.auto_start')),   sprintf("php.ini параметр session.auto_start установлен в On (%s)", ini_get('session.auto_start') ), "Установите его в 'On' в php.ini" false);
check(!check_flag(ini_get('short_open_tag')),      sprintf("php.ini параметр short_open_tag установлен в Off (%s)",   ini_get('short_open_tag')   ), "Установите его в 'Off' в php.ini", false);
check(! ini_get('variables_order') == 'GPCS',         sprintf("php.ini параметр variables_order установлен в GPCS (%s)", ini_get('variables_order') ) ,       "Установите его в 'GPCS' в php.ini", false);
check( ini_get('date.timezone') == 'Europe/Moscow',  sprintf("php.ini параметр variables_order установлен в Europe/Moscow (%s)",ini_get('date.timezone')) ,"Установите его в 'Europe/Moscow' в php.ini", false);

if ( ! is_cli()) {
    echo PHP_EOL.'</pre></body></html>';
}