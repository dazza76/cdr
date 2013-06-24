<?php
/**
 * Model class  - Model.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * Model class
 *
 * @package     model
 */
abstract class Model
{
const CLASS_NAME = __CLASS__;
    static protected $_rules = array();

    protected function __construct($data = null)
    {
        if ($data !== null) {
            $this->setProperty($data);
        }
        if ($this->rules('data') && !$this->data) {
                $this->data = array();
        }
    }

    /**
     * @param string $name имя свойства
     * @return array|string вассив правила проверк модели таблицы.
     */
    abstract public function rules($name = null);

    /**
     * Установить свойство объекта, преобразуя его в указанный тип.
     *
     * @param string $name
     * @param mixed  $value
     * @param string $type
     */
    public function set($name, $value, $type = null)
    {
        if ($name == "data") {
            if ( ! is_array($value)) {
                $value = @json_decode($value, true);
            }
            if ( ! is_array($value)) {
                $value = array();
            }
            $this->$name = $value;
            return;
        }

        switch ($type) {
            case 'json':
                if ( ! is_array($value)) {
                    $value = @json_decode($value, true);
                }
                break;
            case 'id':
                $value = ACPropertyValue::ensurePositive($value);
                break;
            case 'int':
                $value = ACPropertyValue::ensureInteger($value);
                break;
            case 'bool':
                $value = ACPropertyValue::ensureBoolean($value);
                break;
            case 'float':
                $value = ACPropertyValue::ensureFloat($value);
                break;
            case 'date':
            case 'datetime':
            case 'text':
            case 'string':
            default:
                // $value = ACPropertyValue::ensString($value);
                break;
        }

        $this->$name = $value;
    }

    /**
     * Установить параметры
     * @param array|int $data
     */
    public function setProperty($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        } else {
            $this->id = (int) $data;
        }
    }

    /**
     * Возвращает поля модели таблицы и их текущее значение.
     *
     * @param bool $isset
     * @return array
     */
    public function getProperty($isset = false)
    {
        $fields = array();
        $rules  = array_keys($this->rules());

        foreach ($rules as $key) {
            if ($isset) {
                if (isset($this->$key)) {
                    $fields[$key] = $this->$key;
                }
            } else {
                $fields[$key] = $this->$key;
            }
        }

        return $fields;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value, $this->rules($name));
    }

    public function __get($name)
    {
        return $this->$name;
    }
}