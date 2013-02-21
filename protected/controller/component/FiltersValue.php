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