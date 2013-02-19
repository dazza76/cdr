<?php

/**
 * ACDbResult class  - ACDbResult.php file
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * Result class  - Result.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */
class ACDbResult extends MySQLi_Result implements Iterator, Countable {

    /**
     * Количество совпадений (без лимита)
     * @var integer
     */
    public $foundRows;

    /**
     * В случае подсчета при выборге, содержит вспомогательные значения
     * @var array
     */
    public $calc;
    private $_fetchMod;
    private $_key;
    private $_current;

    public function __construct(mysqli $mysqli) {
        parent::__construct($mysqli);
        $this->_key = 0;
    }

    public function __destruct() {
        $this->_key      = null;
        $this->_current  = null;
        $this->foundRows = null;
    }

    /**
     * Список строк масивоми
     * @return array
     */
    public function getFetchAssocs() {
        $rows = array();
        if (!$this->count())
            return $rows;

        $this->data_seek(0);
        while ($row = $this->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Список строк объектами
     * @param string $class_name
     * @param array $params
     * @return array
     */
    public function getFetchObjects($class_name = null, $params = null) {
        $rows = array();
        if (!$this->count())
            return $rows;

        $this->data_seek(0);
        while ($row    = $this->fetch_object($class_name, $params))
            $rows[] = $row;
        return $rows;
    }

    /**
     * ex
     *   setFetchMode('assoc')
     * ex
     *   setFetchMode('object', 'className', $param)
     *
     * @param string $mode
     */
    public function setFetchMode($mode = null) {
        if ($mode === null)
            $this->_fetchMod = null;
        else
            $this->_fetchMod = func_get_args();
    }

    public function fetch() {
        if ($this->_fetchMod)
            return $this->fetch_object($this->_fetchMod[1], $this->_fetchMod[2]);
        else
            return $this->fetch_assoc();
    }

    public function fetchAssoc() {
        if ($this->count())
            return $this->fetch_assoc();
    }

    public function fetchObject($class_name = null, $params = null) {
        if ($this->count()) {
            if (func_num_args() >= 2) {
                return $this->fetch_object($class_name, $params);
            }
            if ($class_name) {
                return $this->fetch_object($class_name);
            } else {
                return $this->fetch_object();
            }
        }
    }

    // --------------------------------------------------------------------------
    // Defined by  Iterator, Countable interface
    // --------------------------------------------------------------------------

    public function count() {
        return @$this->num_rows;
    }

    public function current() {
        return $this->_current;
    }

    public function key() {
        return $this->_key;
    }

    public function next() {
        $this->_current = $this->fetch();
        $this->_key++;
    }

    public function rewind() {
        if ($this->count()) {
            $this->_key     = 0;
            $this->data_seek(0);
            $this->_current = $this->fetch();
        }
    }

    public function valid() {
        return $this->_key < $this->count();
    }

}