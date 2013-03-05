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
    protected $_filters       = array();
    protected $_sessionParams = false;

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var array
     */
    public $dataPage = array();

    /**
     * @var string имя контроллера
     */
    public $page;

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
                    $method = array('$this', array_shift($param_arr));
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
     * @return void
     */
    public function init($params = null) {
        if ($params === null) {
            $params               = $_GET;
            $this->_sessionParams = true;
        }
        $keys                 = array_keys($this->_filters);
        foreach ($keys as $key) {
            $this->$key = $params[$key];
            unset($params[$key]);
        }
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        if ($this->_actType === self::TYPE_ACTION) {
            $action = "action" . $this->_atcion;
            if ( ! method_exists($this, $action)) {
                $this->content = $action . "  error action";
                return;
            }
        } else {
            $action = 'index';
        }

        $this->$action();
    }

    abstract public function index();

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

    protected function _addJsParam($key, $value) {
        $this->dataPage['js'][$key] = $value;
    }

    protected function _addJsSrc($file) {
        $this->dataPage['links'] .= '<script src="js/' . $file . '"></script>' . "\n";
    }

    protected function _addCssLink($file) {
        $this->dataPage['links'] .= ' <link rel="stylesheet" href="css/' . $file . '">' . "\n";
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
}