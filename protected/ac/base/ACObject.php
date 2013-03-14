<?php
/**
 * ACObject class  - ACObject.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * ACObject class
 *
 * @package		AC
 */
class ACObject {

    function __construct($data = null) {
        if ($data !== null)
            $this->merge($data);
    }

    public function merge($obj) {
        foreach ($obj as $k => $v) {
            $this->$k = $v;
        }
    }

    public function mergeRecursive($obj) {
        foreach ($obj as $k => $v) {
            if (is_array($v) || is_object($v)) {
                if (!is_object($this->$k))
                    $this->$k = new self();
                $this->$k->mergeRecursive($v);
            } else {
                $this->$k = $v;
            }
        }
    }

}

/**
 * ACDataObject class
 *
 * @package		AC
 */
class ACDataObject extends ACObject implements IteratorAggregate, Countable {
    /**
     * @var array
     */
    protected $_data = array();

    public function __construct($data = null) {
        parent::__construct();
        if ($data !== null) {
            foreach ($data as $k => $v) {
                $this->_data[$k] = $v;
            }
        }
    }

    public function __get($name) {
        return $this->_data[$name];
    }

    public function __isset($name) {
        return (array_key_exists($name, $this->_data));
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __unset($name) {
        unset($this->_data[$name]);
    }

    public function count() {
        return coun($this->_data);
    }

    public function getIterator() {
        return new ArrayIterator($this->_data);
    }

    public function toArray() {
        $arr = array();
        foreach($this as $key=>$value) {
            $arr[$key] = $value;
        }
        return $arr;
    }

}

/**
 * ACListObject class
 *
 * @package		AC
 */
class ACListObject implements IteratorAggregate, Countable {

    protected $_data   = array();
    private $_couunt = 0;

    function __construct(array $data = array()) {
        $this->_data   = $data;
        $this->_couunt = count($this->_data);
    }

    public function __get($param) {
        $arr = array();
        foreach ($this->_data as $object) {
            $arr[] = $object->$param;
        }
        return $arr;
    }

    public function __set($param, $value) {
        foreach ($this->_data as $object) {
            $object->$param = $value;
        }
    }

    public function addAt($object, $index = null) {
        if ($index === null)
            array_push($this->_data, $object);
        elseif ($index === 0) {
            array_unshift($this->_data, $object);
        } else {
            $arr = array();
            for ($i = 0; $i < $this->_couunt; $i++) {
                if ($i == $index) {
                    array_push($arr, $object);
                }
                array_push($arr, $this->_data[$i]);
            }
            $this->_data   = $arr;
        }
        $this->_couunt = count($this->_data);
    }

    /**
     * @return self
     */
    public function getUnique() {
        $arr = array();
        foreach ($this->_data as $object) {
            if (!in_array($object, $arr))
                array_push($arr, $object);
        }
        return new self($arr);
    }

    public function getItemsAtAttr($param, $value) {
        $arr = array();
        foreach ($this->_data as $object) {
            if ($object->$param == $value)
                array_push($arr, $object);
        }
        return new self($arr);
    }

    public function removeAt($index = null) {
        if ($index === null)
            $index         = $this->_couunt - 1;
        unset($this->_data[$index]);
        $this->_data   = array_values($this->_data);
        $this->_couunt = count($this->_data);
    }

    public function first() {
        return $this->_data[0];
    }

    public function last() {
        return $this->_data[$this->_couunt - 1];
    }

    /**
     *
     * @param integer $index
     * @return stdObject
     */
    public function itemAt($index) {
        return $this->_data[$index];
    }

    public function count() {
        return $this->_couunt;
    }

    public function toArray() {
        return $this->_data;
    }

    public function getIterator() {
        return new ArrayIterator($this->_data);
    }

}

/**
 * ACObjectArray class
 *
 * @package		AC
 */
class ACArrayObject implements IteratorAggregate, ArrayAccess, Countable {

    /**
     * @var array
     */
    protected $_data = array();

    /**
     */
    public function __construct($data = null) {

    }

    /**
     * Слить
     * @param array $data
     */
    public function merge($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                $this->_data[$key] = $value;
            }
        }
    }

    /**
     * Проверить существование значение
     * @param string $key
     * @return boolean
     */
    public function exist($key) {
        return (array_key_exists($key, $this->_data));
    }

    /**
     * Установить значение
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $this->_data[$key] = $value;
    }

    /**
     * Возвращает значение
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        return $this->_data[$key];
    }

    /**
     * Удалить значение
     * @param string $key
     */
    public function remove($key) {
        unset($this->_data[$key]);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __isset($name) {
        return $this->exist($name);
    }

    public function __set($name, $value) {
        $this->set($name, $value);
    }

    public function __unset($name) {
        $this->remove($name);
    }

    public function offsetExists($offset) {
        return $this->exist($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset) {
        return $this->remove($offset);
    }

    public function count() {
        return coun($this->_data);
    }

    public function getIterator() {
        return new ArrayIterator($this->_data);
    }

}