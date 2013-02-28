<?php
/**
 * FiltersValue class  - FiltersValue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * FiltersValue class
 *
 * @package		AC
 */
class FiltersValue {

    const NAME = 'FiltersValue';

    /**
     * @param mixed $id
     * @return int
     */
    public static function parseId($id) {
        return ACPropertyValue::ensurePositive($id);
    }

    /**
     * @param mixed $comment
     * @return string
     */
    public static function parseComment($comment) {
        return trim(ACPropertyValue::ensureString($comment));
    }

    /**
     * @param mixed $date
     * @param ACDateTime $default
     * @return ACDateTime
     */
    public static function parseDatetime($date, ACDateTime $default = null) {
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
    public static function parsePhone($phone) {
        $phone = ACPropertyValue::ensureString($phone);
        return ($phone) ? $phone : null;
    }

    /**
     * @param mixed $offset
     * @return int
     */
    public static function parseOffset($offset, $limit = 30) {
        $offset = ACPropertyValue::ensurePositive($offset);
        $limit  = self::parseLimit($limit);
        return ($offset % $limit != 0) ? 0 : $offset;
    }

    /**
     * @param mixed $limit
     * @return int
     */
    public static function parseLimit($limit) {
        $limit = ACPropertyValue::ensurePositive($limit);
        return ($limit < 30) ? 30 : $limit;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function parseFileExist($value) {
        return ($value == '1');
    }

    /**
     * @param mixed $oper
     * @return int|null
     */
    public static function parseOper($oper) {
        $oper = ACPropertyValue::ensurePositive($oper);
        return ($oper > 0) ? $oper : null;
    }

    /**
     * @param mixed $coming
     * @return int|null
     */
    public static function parseComing($coming) {
        if ($coming == Cdr::INCOMING || $coming == Cdr::OUTCOMING)
            return $coming;
    }

    /**
     * @param mixed $queue
     * @return array|null
     */
    public static function parseQueue($queue) {
        if ($queue) {
            return ACPropertyValue::ensureFields($queue, null);
        }
    }

    /**
     *
     * @param string $sort
     * @param array $sortColumn
     * @return string
     */
    public static function parseSort($sort, array $sortColumn) {
        return (in_array($sort, $sortColumn)) ? $sort : $sortColumn[0];
    }

    /**
     * @param string $desc
     * @return bool
     */
    public static function parseDesc($desc) {
        return ($desc) ? 1 : 0;
    }

    /**
     * @param mixed $status
     * @return string
     */
    public static function parseStatus($status) {
        switch ($status) {
            case "ABANDON":
            case "COMPLETEAGENT":
            case "COMPLETECALLER":
            case "TRANSFER":
                return $status;
                break;
        }
    }

    /**
     * @param mixed $vip
     * @return bool
     */
    public static function parseVIP($vip) {
        return ($vip) ? true : false;
    }

    /**
     * @param mixed $param
     * @return bool
     */
    public static function parseCheck($param) {
        return ($param) ? true : false;
    }
}