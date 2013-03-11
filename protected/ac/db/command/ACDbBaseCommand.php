<?php
/**
 * ACDbBaseCommand class  - BaseCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACDbBaseCommand class.
 *
 * @package AC
 */
abstract class ACDbBaseCommand {

    protected $_dbConnection;

    /**
     * @var array
     */
    protected $_query = array();

    /**
     * Используемые таблицы
     * @var array
     */
    protected $_tables = array();

    /**
     * Команда
     * @var string
     */
    protected $_command;

    public function __construct(ACDbConnection $dbConnection) {
        $this->_dbConnection = $dbConnection;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString() {
        @extract($this->_query, EXTR_SKIP);

        $sql = $this->_command;

        if (isset($distinct))
            $sql.="\nDISTINCT";

        if (isset($calc))
            $sql.="\nSQL_CALC_FOUND_ROWS";

        if (isset($ignore))
            $sql.="\nIGNORE";

        if (isset($command))
            $sql.="\n" . $command;


        if (isset($from))
            $sql.="\nFROM " . $from;

        if (isset($join))
            $sql.="\n" . (is_array($join) ? implode("\n", $join) : $join);

        if (isset($set))
            $sql.="\nSET " . $set;

        if (isset($where))
            $sql.="\nWHERE " . $where;

        if (isset($group))
            $sql.="\nGROUP BY " . $group;

        if (isset($having))
            $sql.="\nHAVING " . $having;

        if (isset($order))
            $sql.="\nORDER BY " . $order;

        if (isset($limit))
            $sql.="\nLIMIT " . $limit;

        if (isset($union))
            $sql.= "\nUNION (\n" . (is_array($union) ? implode("\n) UNION (\n",
                                                               $union) : $union) . ')';

        return $sql;
    }

    /**
     * Выполнить запрос
     * @return ACDbResult
     */
    public function query() {
        $result = $this->_dbConnection->query($this->toString());
        return $result;
    }

    /**
     * Экранирует значение
     * @param string $value
     */
    protected function _quoteValeu(&$value) {
        $value = $this->_dbConnection->quoteEscapeString($value);
        return $value;
    }
}

/**
 * ACDbWhereCommand class.
 *
 * @package AC
 */
abstract class ACDbWhereCommand extends ACDbBaseCommand {

    /**
     *
     * @param array|string $cols сортировать поля
     * @return self
     */
    public function order($cols) {
        if ($this->_query['order']) {
            $this->_query['order'] .= ", ";
        }
        $this->_query['order'] .= implode(",",
                                          ACPropertyValue::ensureFields($cols));
        return $this;
    }

    /**
     *
     * @param array|string $cols группировать поля
     * @return self
     */
    public function group($cols) {
        $cols = implode(",", ACPropertyValue::ensureFields($cols));
        if ( ! $cols) {
            return $this;
        }
        if ($this->_query['group']) {
            $this->_query['group'] .= ", ";
        }
        $this->_query['group'] .= $cols;
        return $this;
    }

    /**
     * Добавить условие (безопасное).
     * Условие разбираеться.
     *
     * @param string $column        - поле
     * @param string $value         - значенние
     * @param string $partialMatch  - условие "<>", "<=", ">=", "<", ">", "=", "IN", "ALL", "ANY", "LIKE", "NOT LIKE"
     * @param string $operator      - оператор объединения "AND", "OR", "XOR"
     * @param boolean $escape       - флаг, надо ли экранровать значение. по умолчанию true
     * @return self
     */
    public function addWhere($column, $value, $partialMatch = '=',
                             $operator = 'AND', $escape = true) {
        if (in_array($partialMatch, array("<>", "<=", ">=", "<", ">", "="))) {
            if ($escape) {
                $this->_quoteValeu($value);
            }
            $where = "$column $partialMatch $value";
        }

        if (in_array($partialMatch, array("IN", "ALL", "ANY"))) {
            $value = ACPropertyValue::ensureFields($value);
            if ($escape) {
                array_walk($value, array($this, "_quoteValeu"));
            }
            if (count($value) == 0) {
                $value = array("NULL");
            }
            $where = $column . " " . $partialMatch
                    . " ( " . implode(", ", $value) . " )";
        }

        if (in_array($partialMatch, array("LIKE", "NOT LIKE"))) {
            if ($escape) {
                $this->_quoteValeu($value);
            }
            $where = $column . " " . $partialMatch . " " . $value;
        }

        if (in_array($partialMatch, array("BETWEEN", "NOT BETWEEN"))) {
            if (is_array($value)) {
                if ($escape) {
                    array_walk($value, array($this, "_quoteValeu"));
                }
                $value = implode(' AND ', $value);
            }
            $where = $column . " " . $partialMatch . " " . $value;
        }


        if ($this->_query['where']) {
            if (( ! in_array($operator, array("AND", "OR", "XOR")))) {
                Log::error("Invalid operator Where : " . $operator);
                $operator = "AND";
            }
            $this->_query['where'] .= " " . $operator . " ";
        }

        $this->_query['where'] .= $where;

        return $this;
    }

    /**
     * Жесткое условие, без экронирования.
     * В случие масива параметры соединены 'AND'
     *
     * @param array|string $where - жесткое условие
     * @return self
     */
    public function where($where) {
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $where[$key] = " $key = $value ";
            }
            $where       = implode('AND', $where);
        }

        $this->_query['where'] .= $where;

        return $this;
    }

    /**
     * Смещение
     * @param integer $offset
     * @return self
     */
    public function offset($offset) {
        $this->_query['offset'] = $offset;
        return $this;
    }

    /**
     * Лимит выводимих записей
     * @param integer $limit
     * @return self
     */
    public function limit($limit) {
        $this->_query['limit'] = $limit;
        return $this;
    }
}