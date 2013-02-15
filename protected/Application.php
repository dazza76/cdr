<?php

/**
 * Application class
 */
class Application {

    /**
     * @var Application
     */
    protected static $_instanse = null;

    /**
     * @return self
     */
    public static function getInstanse() {
        return self::$_instanse;
    }
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @var ACDbConnection
     */
    public $db;

    /**
     * @var ACRequest
     */
    public $request;

    /**
     * @var ACResponse
     */
    public $response;

    /**
     * @var Controller
     */
    public $controller;

    /**
     * Конструктор
     * @param ACConfig $config
     * @throws ACException
     */
    public function __construct($config = null) {
        if (self::$_instanse) {
            throw new ACSingletonException(get_class($this));
        }

        if (is_string($config) && file_exists($config) && is_readable($config)) {
            $config = @include $config;
        }
        if (is_array($config)) {
            App::Config()->mergeRecursive($config);
        }

        $this->db       = ACDbConnection::create(App::Config()->database);
        $this->request  = new ACRequest(App::Config()->webpath);
        $this->response = new ACResponse(array('charset' => App::Config()->charset));

        self::$_instanse = $this;
    }
}

/**
 * App class
 *
 * @package		AC
 */
class App extends Application {

    private static $_config = null;

    /**
     * @return ACObject
     */
    public static function Config() {
        if (self::$_config === null) {
            self::$_config = new ACObject();
        }

        return self::$_config;
    }

    public static function initConfig($file = null) {
        if ($file !== null) {
            $obj = self::loadConfig($file);
            self::Config()->mergeRecursive($obj);
        }
        return self::Config();
    }

    public static function loadConfig($file, $toObj = true) {
        $result = @file_get_contents($file);

        $utf8 = (substr($result, 3, 5) == 'utf-8') ? true : false;

        if ($utf8) {
            $result = substr($result, 8);
        } else {
            $result = ACUtils::iconv($result, 'windows-1251', 'UTF-8');
        }

        $result = ACJSON::decode($result, true);
        $result = ACUtils::iconv($result, 'UTF-8', 'windows-1251');

        if ($toObj) {
            $object = new ACObject();
            $object->mergeRecursive($result);

            return $object;
        } else {
            return $result;
        }
    }

    public static function url($page = null, $params = null) {
        if ($page === null) {
            $page = App::Request()->page;
        }
        $url  = App::Request()->webpath . '/' . $page;

        if (is_array($params)) {
            $p = array();
            foreach ($params as $key => $value) {
                $p[]    = "{$key}={$value}";
            }
            $params = implode("&", $p);
        }
        if ($params !== null) {
            $url .= "?" . $params;
        }

        return $url;
    }

    public static function Db() {
        return parent::$_instanse->db;
    }

    public static function Request() {
        return parent::$_instanse->request;
    }

    public static function Response() {
        return parent::$_instanse->response;
    }

    public static function Controller() {
        return parent::$_instanse->controller;
    }
    // -----------------------------------------------------------------------
}
