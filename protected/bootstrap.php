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
// ----------------------------------------------------------------------------
// Добавлять в отчет все PHP ошибки
error_reporting(E_ALL & ~E_NOTICE);

//header("Content-Type: text/html; charset=UTF-8");

defined('TIME_START') or define('TIME_START', microtime(true));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('DEBUG') or define('DEBUG',(@$config['debug']) ? 1 : 0);
defined('BR') or define('BR', '<br />' . PHP_EOL);
defined('APPPATH') or define('APPPATH', dirname(__FILE__) . DS);
defined('ROOT') or define('ROOT', dirname(dirname(__FILE__)) . DS);
// ----------------------------------------------------------------------------


require_once 'ac/AC.php';
require_once 'ac/base/ACException.php';
require_once 'ac/base/ACLoader.php';
require_once 'ac/logger/ACLog.php';

ACLoader::init();

$cfgsys = @include_once 'config/system.php';
App::Config()->mergeRecursive($cfgsys);
if (@$config['config']) {
    $cfgsys = @include_once 'config/system.'.$config['config'].'.php';
    App::Config()->mergeRecursive($cfgsys);
}
unset($cfgsys);
if (is_array($config)) {
    App::Config()->mergeRecursive($config);
}



ACLog::enable(DEBUG);
if ( ! DEBUG) {
    ini_set('display_errors', 'Off');
}
