<?php
/**
 * ACMysqli class  - ACMysqli.php file
 *
 * Модернизированый класс Mysqli
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * Класс расширяет mysqli
 *
 * @package    AC
 */
class ACDbConnection extends mysqli {

    /**
     * @var ACDbConnection
     */
    private static $_instance = null;

    /**
     * Получить инициализированый объект.
     * @return ACDbConnection объект инициализации
     * @throws ACException - генерируется NOT_INSTANCE если объект не инициализирован
     */
    public static function getInstance() {
        if ( ! ACDbConnection::$_instance) {
            throw new ACException('Экземпляр объекта не инициализирован.');
        }
        return ACDbConnection::$_instance;
    }

    /**
     * Инициализация объекта.
     * @param ACDbConnection $mysqli
     */
    public static function setInstance(ACDbConnection $mysqli) {
        ACDbConnection::$_instance = $mysqli;
    }

    /**
     * Создать новое подключение к БД, и инициализирует его
     *
     * @param  array $config - объект конфигурации. Необходимо передать секцию
     *                            database конфигурации.
     * @return ACDbConnection - объект подключения к БД
     * @throws ACException - генерируется CF_IVALID_SECTION если передана не та секция
     */
    public static function create($config) {
        $cfg = (array) $config;
        @extract($cfg);

        $mysqli = new self(@$host, @$user, @$pass, @$dbname, @$params);

        self::setInstance($mysqli);

        return $mysqli;
    }
    // ------------------------------------------------------------------------
    // object
    // ------------------------------------------------------------------------

    /**
     * Имеються ли зоблокированные таблицы
     * @var boolean
     */
    public $tablesLock;

    /**
     * @var boolean
     */
    public $success  = false;
    //
    private $_numQuery;
    private $_timeQuery;
    private $_queryArr;
    private $_databaseName;
    private $_currentQuery;
    private $_connect = false;
    // params
    private $_params = array(
        "exception" => 0, // выброс ошибок
        "log"       => 1, // введение логов о запросах
        "time_log"  => 0
    );

    /**
     * Создать новое подключение к БД.
     *
     * @param string $host - хост
     * @param string $user      - логин
     * @param string $pass      - пароль
     * @param string $dbname  - база
     * @param ACConfig $tables  - таблицы базы
     * @param array $params     - параметры
     * @throws ACDbConnectionException
     */
    public function __construct($host, $user, $pass, $dbname, $params = null) {
        $params              = (array) $params;
        $this->_numQuery     = 0;
        $this->_timeQuery    = 0;
        $this->_queryArr     = array();
        $this->_databaseName = $dbname;
        $this->_params       = array_replace($this->_params, $params);

        $time_start = microtime(true);

        @parent::__construct($host, $user, $pass, $dbname);
        if (mysqli_connect_errno()) {
            $this->_databaseName = null;
            /// if ($this->params["exception"])
                throw new ACDbConnectionException();
            $this->_connect      = false;
            return;
        }

        $this->_timeQuery = microtime(true) - $time_start;
        $this->_connect   = true;
        $this->success    = true;
        log::trace("connect database: '{$dbname}'");

        if ($params['charset']) {
            $this->set_charset($params['charset']);
        }
        if ($params['time_zone']) {
            $this->quickQuery('SET time_zone = "'.$params['time_zone'].'"');
        }
    }

    public function __destruct() {
        $this->_numQuery     = null;
        $this->_timeQuery    = null;
        $this->_queryArr     = null;
        $this->_databaseName = null;
    }

    /**
     * Быстрое выполнение запроса, без вормирования результата
     * @param string $query
     * @return boolen
     */
    public function quickQuery($query) {
        if ( ! $this->_connect)
            return;

        $time_start          = microtime(true);
        $this->_currentQuery = $query;

        $this->real_query($query);

        $this->_log($query, $time_start);

        if ($this->errno) {
            if (@$this->_params['exception']) {
                throw new ACDbSqlException($this->errno, $this->error, $query);
            }
            $this->success = false;
        } else {
            $this->success = true;
        }

        return $this->success;
    }

    /**
     * Выполняет SQL-запрос и возврощает расширенный результат
     * @param string $query
     * @return ACDbResult
     */
    public function query($query) {
        $this->quickQuery($query);
        $result = new ACDbResult($this);
        return $result;
    }

    /**
     * Запись логов, если установлено в параметрах
     * @param string $query
     * @param float  $time_start
     */
    protected function _log($query, $time_start) {
        $this->_numQuery += 1;
        $time             = microtime(true) - $time_start;
        $this->_timeQuery = $this->_timeQuery + $time;

        if ( ! @$this->_params["log"])
            return;

        $time = sprintf("%01.5f", microtime(true) - $time_start);

        log::trace("{$query}", 'SQL' );
        if ($this->errno) {
            log::error("[Error] |-> " . $this->errno . "; " . $this->error . "; ");
        }
        else {
            // log::trace("[OK] |-> [time: {$time}][affect: {$this->affected_rows}]");
        }

        $this->_queryArr[] = array(
            "status" => $this->sqlstate,
            "time"   => $time,
            "query"  => $query,
            "affect" => $this->affected_rows
        );
    }

    /**
     * Выброс исключения, если установлено в параметрах
     * @param string $query
     * @param float  $time_start
     */
    protected function _throwExc($query) {
        if ( ! @$this->_params['exception'])
            return;

        throw new ACDbSqlException($this->errno, $this->error, $query);
    }

    /**
     * Форматированные команды запросы
     * @return ACDbCommand
     */
    public function createCommand() {
        return new ACDbCommand($this);
    }

    /**
     * Количество запросов.
     * @return integer
     */
    public function getNumQuery() {
        return $this->_numQuery;
    }

    /**
     * Время затраченное на запросы к базе данных.
     * @return float
     */
    public function getTimeQuery() {
//        return $this->_timeQuery;
        return round($this->_timeQuery, 5);
    }

    /**
     * Список всех запросов.
     * Возвращает список масивов
     *
     *  return array(
     *  - [0] => array( "time"  => 0.001 ,
     *  -               "query" => "SELEC * FROM table" ),
     *  - [1] => array( "time"  => 0.001 ,
     *  -               "query" => "SELEC * FROM table" ),
     *  - ...
     *  );
     *
     * @return array масив запросов.
     */
    public function getQueryArr() {
        return $this->_queryArr;
    }

    /**
     * Имя базы данных
     * @return string
     */
    public function getDatabase() {
        return $this->_databaseName;
    }

    /**
     * Экранирует спецсимволы и заключает строку в одинарные кавычки
     * @param string $value - строка
     * @return string
     */
    public function quoteEscapeString(&$value) {
        $value = "'" . $this->real_escape_string($value) . "'";
        return $value;
    }

    /**
     * Котировки имя таблицы для использования в запросе.
     * @param string $name имя таблицы
     * @return string правильная цитата имени таблицы
     */
    public function quoteTableName($name) {
        return '`' . $name . '`';
    }

    /**
     * Котировки имя столбца для использования в запросе.
     * @param string $name имя столбца
     * @return string правильная цитата имени столбца
     */
    public function quoteColumnName($name) {
        return '`' . $name . '`';
    }

    /**
     * Экранирует спецсимволы
     * @param string $string - строка
     * @return string
     */
    public function escapeString($string) {
        $string = $this->real_escape_string($string);
        return $string;
    }
}

/**
 * Исключительная ситуация, возникаюшая при возникновении ошибки обработки SQL-запроса
 * @package    AC.error
 */
class ACDbSqlException extends ACException {

    //#1062 - Duplicate entry 'login' for key 'login'
    //#1062 - Duplicate entry '1' for key 'PRIMARY'
    //«#1061 – Duplicate key name»

    private $_error;
    private $_errno;
    private $_query;

    public function __construct($errno, $error, $query) {
        $this->_errno = $errno;
        $this->_error = $error;
        $this->_query = $query;
        parent::__construct('Ошибка запроса к базе. #' . $errno . ' - "' . $error . '". "' . $query . '".');
    }

    public function toString() {
        return "# Error {$this->_errno}: {$this->_error} .";
    }

    public function getQuery() {
        return $this->_query;
    }

    public function getError() {
        return $this->_error;
    }

    public function getErrno() {
        return $this->_errno;
    }
}

/**
 * Исключительная ситуация, возникаюшая при подключении к базе данных
 *
 * @package    AC.error
 */
class ACDbConnectionException extends ACException {

    public function __construct() {
        parent::__construct('Ошибка подключения к базе даных.');
    }
}
