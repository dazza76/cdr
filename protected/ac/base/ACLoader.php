<?php
/**
 * ACLoader class  - ACLoader.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * ACLoader class
 *
 * @package		AC
 */
class ACLoader {

    private static $_singleton = false;
    private static $_classes   = array();

    protected static function _init() {
        if (self::$_singleton) {
            return;
        }

        if ( ! defined('APPPATH')) {
            throw new ACException("Непроинициализирована константа APPPATH");
        }

        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }

        spl_autoload_register(array(__CLASS__, 'autoload'));
        self::$_singleton = true;
    }

    public static function init() {
        self::_init();

        $file = APPPATH . 'system/cache/classes.php';
        if (file_exists($file) && is_readable($file)) {
            $classes = include $file;
            self::classes($classes);
        }
    }

    public static function classes(array $classes = null) {
        self::_init();

        if ($classes === null) {
            return self::$_classes;
        }

        self::$_classes = array_merge(self::$_classes, $classes);
    }

    public static function autoload($pClassName) {
        // Либо уже загружен, или этот класс не принадлежит к семейству core.
        if (class_exists($pClassName)) {
            return true;
        }

        $pClassName = strtolower($pClassName);
        if ( ! isset(self::$_classes[$pClassName])) {
            return false;
        }

        $file = self::$_classes[$pClassName];
        $file = APPPATH . $file;

        if (file_exists($file) && is_readable($file)) {
            require ( $file );
            if (class_exists($pClassName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Просканировать директорию в поиска классов.
     * @param string $dir
     * @param boolen $cutpath
     * @return array
     */
    public static function getClassesDir($dir, $cutpath = false) {
        $classes = array();
        if ($cutpath) {
            if ( ! is_string($cutpath)) {
                $cutpath = $dir;
            }
        }
        self::_scandir($dir, $classes, $cutpath);

        return $classes;
    }

    /**
     *
     * @param string  $dir      папка сканирования
     * @param array   $classes  ссылка на масив классов
     * @param string  $cutpath  путь, который необходимо отрезать
     */
    private static function _scandir($dir, &$classes, $cutpath = null) {
        $files = scandir($dir);

        foreach ($files as $file) {
            $filename = $dir . $file;
            if ($file != "." && $file != ".." && is_dir($filename) && $filename != 'doc') {
                self::_scandir($filename . DIRECTORY_SEPARATOR, $classes,
                               $cutpath);
            } else if (is_file($filename)) {
                if (preg_match("/\.php$/", $filename)) {
                    self::_scanfile($filename, $classes, $cutpath);
                }
            }
        }
    }

    /**
     *
     * @param string  $file     файл поика классов
     * @param array   $classes  ссылка на масив классов
     * @param string  $cutpath  путь, который необходимо отрезать
     */
    private static function _scanfile($file, &$classes, $cutpath = null) {
        // Рассчитываем имя файла в котором хранится текст
        // Читаем нужную страницу
        $text = file($file);

        if ( ! empty($text)) {
            // Находим текст страницы
            $text = implode("", $text);
            $reg  = '#class[\s]+([a-z0-9_]+)[\sa-z0-9_,]*\{#is';
            preg_match_all($reg, $text, $regs);
            if (isset($regs[1]) && is_array($regs[1])) {
                $regs = $regs[1];
                foreach ($regs as $class_name) {
                    $class_name = strtolower($class_name);
                    if ($cutpath) {
                        $classes[$class_name] = substr($file, strlen($cutpath));
                    } else {
                        $classes[$class_name] = $file;
                    }
                }
            }
        }
    }
}