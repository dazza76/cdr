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
     * @var string тип действия контролера (page, method, ajax)
     */
    protected $_actType;
    protected $_action;
    protected $_sortColumn = array();
    protected $_filters    = array();

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var array
     */
    public $dataPage = array();

    /**
     * @var string
     */
    public $page;

    /**
     * @var array
     */
    public $info;

    /**
     * @var array
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
            if ($this->_filters[$name][0]) {
                $method = $this->_filters[$name][0];
            } else {
                $method = '_parse' . $name;
            }
            $value  = $this->$method($value, $this->_filters[$name][1]);
        }

        $this->$name = $value;
    }

    /**
     * Автоматическая инициализация
     * @return void
     */
    public function init($params = null) {
        if ($params === null) {
            $params = $_GET;
        }
        $keys   = array_keys($this->_filters);
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
     * Выполнить шаблонный файл
     * @param string $file имя файла
     * @return string
     */
    public function view($file) {
        ob_start();
        include APPPATH . 'view/' . $file; //main.php';
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function mainView($file) {
        $this->content = $this->view($file);
        $this->content = $this->view('layout/main.php');
        return $this->content;
    }

    public function render($print = true) {
        ac_trace('Запросов MySQL: ' . App::Db()->getNumQuery());
        ac_trace('Время обработки MySQL: ' . sprintf(" %01.6f",
                                                     App::Db()->getTimeQuery()));
        ac_trace('Время работы скрипта : ' . sprintf(" %01.6f",
                                                     ACUtils::getExecutionTime()));

        return $this->_print($this->content, $print);
    }

    protected function _print($string, $print = true) {
        if ($print)
            echo $string;
        else
            return $string;
    }

    /**
     * @param mixed $id
     * @return int
     */
    protected function _parseId($id) {
        $id = ACPropertyValue::ensurePositive($id);
        return $id;
    }

    /**
     * @param mixed $comment
     * @return string
     */
    protected function _parseComment($comment) {
        return trim(ACPropertyValue::ensureString($comment));
    }

    /**
     * @param mixed $date
     * @param ACDateTime $default
     * @return \ACDateTime
     */
    protected function _parseDatetime($date, ACDateTime $default = null) {
        if ($date instanceof DateTime) {
            return new ACDateTime($date->format(ACDateTime::DATETIME));
        }

        if ($date instanceof ACDateTime) {
            return $date;
        }

        if ($default === null) {
            $default = new ACDateTime();
        }

        $date = ACPropertyValue::ensureDatetime($date);
        if ($date == '0000-00-00 00:00:00') {
            $date = $default;
        } else {
            $date = new ACDateTime($date);
        }
        return $date;
    }

    /**
     * @param mixed $phone
     * @return string|null
     */
    protected function _parsePhone($phone) {
        $phone = ACPropertyValue::ensureString($phone);
        if ($phone)
            return $phone;
    }

    /**
     * @param mixed $offset
     * @return int
     */
    protected function _parseOffset($offset) {
        $offset = ACPropertyValue::ensurePositive($offset);
        $limit  = $this->_parseLimit($this->limit);
        if ($offset % $limit != 0)
            $offset = 0;
        return $offset;
    }

    /**
     * @param mixed $limit
     * @return int
     */
    protected function _parseLimit($limit) {
        $limit = ACPropertyValue::ensurePositive($limit);
        if ($limit < 30)
            $limit = 30;
        return $limit;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function _parseFileExist($value) {
        return ($value == '1');
    }

    /**
     * @param mixed $oper
     * @return int|null
     */
    protected function _parseOper($oper) {
        $oper = ACPropertyValue::ensurePositive($oper);
        if ($oper > 0)
            return $oper;
    }

    /**
     * @param mixed $coming
     * @return int|null
     */
    protected function _parseComing($coming) {
        if ($coming == Cdr::INCOMING || $coming == Cdr::OUTCOMING)
            return $coming;
    }

    protected function _parseQueue($queue) {
        if ($queue)
            return ACPropertyValue::ensureNumbers($queue, null);
    }

    protected function _parseSort($sort) {
        if (in_array($sort, $this->_sortColumn)) {
            return $sort;
        } else {
            return $this->_sortColumn[0];
        }
    }

    protected function _parseDesc($desc) {
        return ($desc) ? 1 : 0;
    }
}