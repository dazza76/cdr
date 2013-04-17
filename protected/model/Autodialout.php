<?php

/**
 * Autodialout class  - Autodialout.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * Autodialout class
 *
 * @property string
 *
 * @property string  $id             - [0] id
 * @property string  $dialnum        - [1] phone
 * @property ACDateTime  $datetime   - [2] date
 * @property string  $type           - [3] type
 * @property ACDateTime  $datetotell - [4] datecall
 * @property string  $dateofcall     - [5]
 * @property string  $retries        - [6] retries
 * @property string  $result         - [7] result
 * @property string  $clinic         - [8]
 * @property string  $doctorspec     - [9]
 * @property string  $doctorname     - [10]
 * @property string  $comment        - [11]
 * @property string  $dateofremind   - [12]
 * @property string  $record         - [13]
 */
class Autodialout extends ACDataObject {
    /** @var string название таблицы     */

    const TABLE = 'autodialout';

    public function __construct($row = null) {
        if ($row != null) {
            $this->id = $row[0];
            $this->dialnum = $row[1];
            $this->datetime = FiltersValue::toFormatDate($row[2]);
            $this->type = $row[3];
            $this->datetotell = $row[4];
            $this->dateofcall = $row[5];
            $this->retries = $row[6];
            $this->result = $row[7];
            $this->clinic = $row[8];
            $this->doctorspec = $row[9];
            $this->doctorname = $row[10];
            $this->comment = $row[11];
            $this->dateofremind = $row[12];
            $this->record = $row[13];
        }
    }

    public function __set($name, $value) {
        if ($name == App::Config()->autoinform['datetime']) {
            $name = 'datetime';
            $value = FiltersValue::toFormatDate($value, true);
        }
        if ($name == 'datetotell') {
            $value = FiltersValue::toFormatDate($value, true);
        }
        parent::__set($name, $value);
    }

    public function getDialnum() {
        return "+7" . substr($this->dialnum, 1);
    }

}