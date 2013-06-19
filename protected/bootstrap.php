<?php
/**
 * Basic functionality.
 * -- Базовая функциональность.
 *
 * Handles loading of core files needed on every request
 * -- Проводит загрузку основных файлов, необходимых на каждый запрос
 *
 * PHP 5.3
 *
 * @package       AC
 */
error_reporting(E_ALL & ~E_NOTICE);

// Проверка конфигурации apache
//if ( ini_get('magic_quotes_gpc') != "0" ) die ("'magic_quotes_gpc' is not 1");
//if ( ini_get('magic_quotes_runtime') != "0" ) die ("'magic_quotes_runtime' is not 1");
//if ( ini_get('variables_order') != "GPCS" ) die ("'variables_order' is not GPCS");
//if ( ini_get('register_globals') != "Off" ) die ("'register_globals' is not Off");
// ----------------------------------------------------------------------------


//header('Content-Type: text/html; charset=UTF-8');
// ----------------------------------------------------------------------------
defined('TIME_START') or define('TIME_START', microtime(true));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('BR') or define('BR', '<br />' . PHP_EOL);
// ----------------------------------------------------------------------------
defined('ROOT') or define('ROOT', dirname(dirname(__FILE__)) . DS);
defined('APPPATH') or define('APPPATH', dirname(__FILE__) . DS);
defined('VIEWDIR') or define('VIEWDIR', APPPATH . 'view' . DS);
// ----------------------------------------------------------------------------

require_once 'ac/AC.php';
require_once __DIR__.'/autoload.php';

//require_once 'ac/base/ACObject.php';
//require_once 'Application.php';

// Установка систкмной конфигурации
// ---------------------------------------------------------------------------
$_config_system           = @include_once 'config/system.php';
App::Config()->mergeRecursive($_config_system);
$_config_file             = @$_config_system['config'];
if (@$config['config']) {
    $_config_file = $config['config'];
}
// дополнительный файл конфигурации
if (@$_config_file) {
    $_config_system = @include_once 'config/' . $_config_file . '.php';
    App::Config()->mergeRecursive($_config_system);
}
unset($_config_system);
// локальные конфигурации
if (is_array($config)) {
    App::Config()->mergeRecursive($config);
}
// шапка страниц
App::Config()->pages = @include_once 'system/pages.php';


defined('DEBUG') or define('DEBUG', (App::Config()->debug) ? 1 : false);
// Добавлять в отчет все PHP ошибки
if ( ! DEBUG) {
    error_reporting(0);
}

if (!App::Config()->enable_ie) {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
        $header = "Location: http://" . $_SERVER["HTTP_HOST"] . App::Config()->webpath . "/badbrowser.php";
        header($header);
        exit();
    }
}
// ----------------------------------------------------------------------------

//require_once 'ac/AC.php';
//require_once 'ac/base/ACLoader.php';

//ACLoader::init();

Log::enable(DEBUG);
Log::trace('bootstrap');

// CASHE STATIC FILE
App::Config()->v  = 20;