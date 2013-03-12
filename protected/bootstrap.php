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

//header('Content-Type: text/html; charset=UTF-8');



// ----------------------------------------------------------------------------
defined('TIME_START') or define('TIME_START', microtime(true));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('BR') or define('BR', '<br />' . PHP_EOL);
// ----------------------------------------------------------------------------
defined('ROOT') or define('ROOT', dirname(dirname(__FILE__)) . DS);
defined('APPPATH') or define('APPPATH', dirname(__FILE__) . DS);
defined('WEBROOT') or define('WEBROOT', ROOT . 'webroot' . DS);
defined('VIEWDIR') or define('VIEWDIR', APPPATH . 'view' . DS);
// ----------------------------------------------------------------------------

require_once 'ac/base/ACObject.php';
require_once 'Application.php';

// Установка систкмной конфигурации
// ---------------------------------------------------------------------------
$_config_system = @include_once 'config/system.php';
App::Config()->mergeRecursive($_config_system);
App::Config()->supervisor = @include_once 'config/supervisor.php';
$_config_file = @$_config_system['config'];
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


App::Config()->page_prefix = '.php';

defined('DEBUG') or define('DEBUG', (App::Config()->debug) ? 1 : false);
// Добавлять в отчет все PHP ошибки
if (!DEBUG) {
    error_reporting(0);
}
// ----------------------------------------------------------------------------

require_once 'ac/AC.php';
require_once 'ac/base/ACLoader.php';

ACLoader::init();

Log::enable(DEBUG);
Log::trace('bootstrap');
