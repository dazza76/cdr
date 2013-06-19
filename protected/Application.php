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

        if (is_array($config)) {
            App::Config()->mergeRecursive($config);
        }
        $this->db       = ACDbConnection::create(App::Config()->database);
        $this->request  = new ACRequest(App::Config()->webpath);
        $this->response = new ACResponse(array('charset' => App::Config()->charset));

        session_start();

        self::$_instanse = $this;
    }
}

/**
 * App class
 * @package		AC
 */
class App extends Application {

    private static $_config = null;

    /**
     * @return ACObject
     */
    public static function Config($section_load = null) {
        if (self::$_config === null) {
            self::$_config = new ACObject();
        }

        if ($section_load !== null) {
            if (!isset(self::$_config->$section_load)) {
                self::$_config->$section_load = array();
                $filename = APPPATH . "config/{$section_load}.php";
                if (file_exists($filename)) {
                    self::$_config->$section_load = @include $filename;
                }
            }
        }

        return self::$_config;
    }

    /**
     *
     * @param string $page
     * @param string|array $params
     * @return string
     */
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

    /**
     *
     * @param string $page
     * @param array $params
     */
    public static function location($page, $params = null) {
        $host = parent::$_instanse->request->host;
        $webpath = parent::$_instanse->request->webpath;

        $url = $host.$webpath;
        if (substr($url, -1) != "/") {
            $url .= "/";
        }

        if ($params == null) {
            $params = array();
        }
        $url .= $page . ".php?" . http_build_query($params);

        parent::$_instanse->response->header('location', 'http://' . $url);
        parent::$_instanse->response->send();
    }

    /**
     * Тупо перезагружает страницу
     */
    public static function refresh() {
        $host = parent::$_instanse->request->host;
        $page = parent::$_instanse->request->url;

        $pos = strpos($page, '?');
        if ($pos !== false) {
            $page = substr($page, 0, $pos);
        }

        $get      = $_GET;
        $get["r"] = rand();

        $url = $host . $page . "?" . http_build_query($get);

        parent::$_instanse->response->header('location', 'http://' . $url);
        parent::$_instanse->response->send();
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
