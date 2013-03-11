<?php
/**
 * Controller class  - Controller.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * Controller class
 *
 * @package		AC
 */
abstract class Controller {

    const TYPE_ACTION = 'act';
    const TYPE_PAGE   = 'page';

    /**
     * @var string тип действия контролера (page, act, ajax)
     */
    protected $_actType = 'page';
    protected $_action;

    /**
     * ### Вызывает функцию 'filter' парсера
     * e.g `'filter'    => 1`
     *
     * ### Вызывает функцию 'method_name' контролерра
     * e.g `'filter'    => 'method_name'`
     *
     * ### Вызывает функцию 'method_name' парсера с параметром
     * e.g `'filter'    => 'array('method_name', 'param_1')`
     *
     * ### Вызывает функцию 'method_name' контролерра с параметром
     * e.g `'filter'    => 'array('controller', 'method_name', 'param_1')`
     *
     * @var array фильтры
     */
    protected $_filters     = array();
    protected $_filters_url = array();

    /**
     * Текущии используемые фильтры
     * @return array
     */
    public function getFilters() {
        $filters = array();
        if ( ! is_array($this->_filters_url)) {
            return $filters;
        }
        foreach ($this->_filters_url as $k => $v) {
            if ($v) {
                $filters[$k] = $v;
            }
        }
        return $filters;
    }

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var array
     */
    public $dataPage = array();

    /**
     * @var string подстраница
     */
    protected $_section;

    /**
     * @var array доступные подстраницы
     */
    protected $_sections;

    /**
     * @var array
     */
    public $info;

    /**
     * @var array ошибки в ходе работы
     */
    public $error;

    public function __construct() {
        if ( ! empty($_POST['act'])) {
            $this->_atcion  = $_POST['act'];
            $this->_actType = self::TYPE_ACTION;
        }
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        if (array_key_exists($name, $this->_filters)) {
            $filter    = $this->_filters[$name];
            $param_arr = array();
            if (is_array($filter)) {
                $param_arr = $filter;
                $method    = array_shift($param_arr);
            } else {
                $method = $filter;
            }

            switch ($method) {
                case 1:
                    $method = array('FiltersValue', 'parse' . $name);
                    break;
                case 'controller' :
                    $method = array($this, array_shift($param_arr));
                    break;
                default :
                    $method = array('FiltersValue', $method);
                    break;
            }
            array_unshift($param_arr, $value);
            $value  = call_user_func_array($method, $param_arr);
        }

        $this->$name = $value;
    }

    /**
     * Автоматическая инициализация
     * @param array $params параметры инициализации
     * @return void
     */
    public function init($params = null) {
        if ($params === null) {
            $params = $_GET;
        }
        if ( ! is_array($params)) {
            $params         = array();
        }
        $this->_section = $this->_ensureSection($params['section']);
        unset($params['section']);

        $params = $this->_sessionParams($params);

        $keys = array_keys($this->_filters);
        foreach ($keys as $key) {
            $this->$key = $params[$key];
            unset($params[$key]);
        }
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Отображаемая страница по умолчанию
     */
    public function index() {
        $this->viewMain();
    }

    /**
     * Имя контролера
     * @return string имя контроллера (страница)
     */
    public function getPage() {
        $class = strtolower(get_class($this));
        $p     = strpos($class, 'controller');
        $page  = false;
        if ($p !== false) {
            $page = substr($class, 0, $p);
        }
        return $page;
    }

    /**
     * Подстраница
     * @return string
     */
    public function getSection() {
        return $this->_section;
    }

    /**
     * Выполняет шаблонный файл и возвращает результат
     * @param string $file имя файла
     * @return string
     */
    public function getView($file) {
        ob_start();
        include APPPATH . 'view/' . $file; //main.php';
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function getActType() {
        return $this->_actType;
    }

    /**
     * Выполнить шаблонный файл
     * @param string $file имя файла
     * @return void
     */
    public function view($file) {
        ob_start();
        include APPPATH . 'view/' . $file; //main.php';
        $content = ob_get_contents();
        ob_end_clean();

        $this->content = $content;
    }

    /**
     * Выполнить главный шаблонный файл
     * @param type $file
     */
    public function viewMain($file = null) {
        if ($file !== null) {
            $this->view($file);
        }
        if (DEBUG) {
            $this->_addCssLink('aclog.css');
        }

        $this->view('layout/main.php');
    }

    /**
     * Вывести результаты парсированых шаблонов
     * @param bool $print
     * @return string
     */
    public function render($print = true) {
        if (DEBUG) {
            Log::trace('--------------');
            Log::trace('Запросов MySQL: ' . App::Db()->getNumQuery());
            Log::trace('Время обработки MySQL: ' . sprintf(" %01.6f",
                                                           App::Db()->getTimeQuery()));
            Log::trace('Время работы скрипта : ' . sprintf(" %01.6f",
                                                           ACUtils::getExecutionTime()));
        }

        return $this->_print($this->content, $print);
    }

    protected function _print($string, $print = true) {
        if ($print)
            echo $string;
        else
            return $string;
    }

    protected function _addJsParam($key, $value) {
        $this->dataPage['js'][$key] = $value;
    }

    protected function _addJsSrc($file) {
        $this->dataPage['links'] .= '<script src="js/' . $file . '?' . App::Config()->v . '"></script>' . "\n";
    }

    protected function _addCssLink($file) {
        $this->dataPage['links'] .= ' <link rel="stylesheet" href="css/' . $file . '?' . App::Config()->v . '">' . "\n";
    }

    protected function _sessionParams($params) {
        $pg = "pg_" . $this->getPage();
        if ($this->_sections) {
            $pg .= "_" . $this->getSection();
        }

        if (count($params) == 0) {
            $params = @unserialize($_SESSION[$pg]);
            Log::trace('session parametr 1');
        }

        if ( ! is_array($params)) {
            $params = array();
        }

        $_SESSION[$pg] = @serialize($params);

        Log::vardump(array($pg => $params));

        return $params;
    }

    protected function _ensureSection($section = null) {
        if ($section == null) {
            $section = $_GET['section'];
        }
        if ( ! $this->_sections) {
            return null;
        }
        if ( ! array_key_exists($section, $this->_sections)) {
            reset($this->_sections);
            list($section) = each($this->_sections);
        }

        return $section;
    }
}