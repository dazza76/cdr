<?php

/**
 * ACDbSelectCommand class  - ACDbSelectCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACDbSelectCommand class.
 *
 * Выборга из таблици
 *
 * Оператор SELECT имеет следующую структуру:
 * SELECT [STRAIGHT_JOIN]
 *    [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
 *    [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS] [HIGH_PRIORITY]
 *    [DISTINCT | DISTINCTROW | ALL]
 *    select_expression,...
 * [INTO {OUTFILE | DUMPFILE} 'file_name' export_options]
 * [FROM table_references
 * [WHERE where_definition]
 * [GROUP BY {unsigned_integer | col_name | formula} [ASC | DESC], ...]
 * [HAVING where_definition]
 * [ORDER BY {unsigned_integer | col_name | formula} [ASC | DESC], ...]
 * [LIMIT [offset,] rows]
 * [PROCEDURE procedure_name]
 * [FOR UPDATE | LOCK IN SHARE MODE]]
 *
 */
class ACDbSelectCommand extends ACDbWhereCommand {

    protected $_command = 'SELECT';
    private $_calc_tables = null;

    /**
     * Поля необходимые для чтения
     *
     * @param string|array $select
     * @param bool $escape
     */
    public function __construct(ACDbConnection $dbConnection, $select = "*", $escape = false) {
        parent::__construct($dbConnection);
        $this->_query['select'] = array();
        $this->select($select, $escape);
    }

    /**
     * Добавить поле выборги
     * @param array|string $select
     * @param bool $escape
     * @return \ACDbSelectCommand
     */
    public function select($select, $escape = false) {
        $select = ACPropertyValue::ensureFields($select);

        if ($select) {
            if ($escape) {
                array_walk($select, array($this, '_quoteTable'));
            }
            $this->_query['select'] = array_merge($this->_query['select'], $select);
        }
        return $this;
    }

    /**
     * @return ACDbResult
     */
    public function query() {
        $DB = $this->_dbConnection;

        // Блокируем таблицы
        if ($this->_query['calc']) {
            if ((int) $this->_query['limit'] <= 0) {
                $this->_query['limit'] = 15;
            }
            if ((int) $this->_query['offset'] <= 0) {
                $this->_query['offset'] = 0;
            }

            if ($this->_calc_tables) {
                $this->_tables = $this->_calc_tables;
            }
            $tables = $this->_tables;
            array_walk($tables, function(&$value) {
                        $value .= ' READ';
                    });
            $query = 'LOCK TABLES ' . implode(",", $tables);

            $DB->tablesLock = true;

            $DB->query($query);
        }

        $result = parent::query();

        // Количество совпадений (без лимита)
        if ($this->_query['calc']) {
            $result_count = $DB->query("SELECT FOUND_ROWS() as count");
            $count = $result_count->fetch_assoc();

            $result_count->close();
            $result->foundRows = (int) $count['count'];
            $result->calc = array(
                'count' => $result->foundRows,
                'limit' => $this->_query['limit'],
                'offset' => $this->_query['offset']
            );

            $DB->query('UNLOCK TABLES');
            $DB->tablesLock = false;
        }
        return $result;
    }

    public function toString() {
        $query = $this->_query;

        $sql = 'SELECT';

        if (isset($query['distinct']))
            $sql .= ' DISTINCT ';

        if ($query['calc'])
            $sql .= ' SQL_CALC_FOUND_ROWS ';

        $sql .= ' ' . implode(", ", $query['select']);

        if (isset($query['from']))
            $sql.="\nFROM " . $query['from'];

        if (isset($query['join']))
            $sql.="\n" . (is_array($query['join']) ? implode("\n", $query['join']) : $query['join']);

        if (isset($query['where']))
            $sql.="\nWHERE " . $query['where'];

        if (isset($query['group']))
            $sql.="\nGROUP BY " . $query['group'];

        if (isset($query['having']))
            $sql.="\nHAVING " . $query['having'];

        if (isset($query['order']))
            $sql.="\nORDER BY " . $query['order'];

        $limit = isset($query['limit']) ? (int) $query['limit'] : -1;
        $offset = isset($query['offset']) ? (int) $query['offset'] : -1;

        if ($limit > 0 || $offset >= 0) {
            if ($limit <= 0)
                $limit = 15;
            if ($offset < 0)
                $offset = 0;

            $sql .= "\nLIMIT " . $offset . ", " . $limit;
        }

        if (isset($query['union']))
            $sql.= "\nUNION (\n" . (is_array($query['union']) ? implode("\n) UNION (\n", $query['union']) : $query['union']) . ')';

        return $sql;
    }

    /**
     * @param string $table таблицы
     * @return self
     */
    public function from($table) {
        if ($this->_query['from']) {
            $this->_query['from'] .= ",";
        }
        $this->_query['from'] .= (string) $table;
        $this->_tables[] = $table;

        return $this;
    }

    /**
     * Параметр SQL_CALC_FOUND_ROWSpublic возвращает количество строк,
     * которые вернул бы оператор SELECT, если бы не был указан LIMIT.
     * Искомое количество строк можно получить при помощи SELECT FOUND_ROWS()
     *
     * @return self
     */
    public function calc($tables = null) {
        $this->_calc_tables = $tables;
        $this->_query['calc'] = true;
        return $this;
    }

    /**
     *
     * @return self
     */
    public function distinct() {
        $this->_query['distinct'] = true;
        return $this;
    }

    /**
     *
     * @param array|string $tables
     * @return self
     */
    public function addLockTables($tables) {
        $this->_tables = $this->_tables + ACPropertyValue::ensureFields($tables);
        return $this;
    }

    /**
     * Объединение таблиц
     *
     * @param string        $table
     * @param array|string  $using using culums
     * @return self
     */
    public function leftJoinUsing($table, $using) {
        $this->_tables[] = $table;
        $using = implode(ACPropertyValue::ensureFields($using, false));
        $_join .= ' LEFT JOIN ' . $table
                . ' USING(' . $using . ') ';
        $this->_query['join'] .= $_join;
        return $this;
    }

    public function leftJoinOn($table, $field1, $field2, $partialMatch = '=') {
        $this->_tables[] = $table;
        $_join .= ' LEFT JOIN ' . $table
                . " ON $field1  $partialMatch  $field2 ";
        $this->_query['join'] .= $_join;
        return $this;
    }

    /**
     * После группировоное условие
     * Жесткое условие, без экронирования.
     * В случие масива параметры соединены 'AND'
     *
     * @param array|string $having - жесткое условие
     * @return self
     */
    public function having($having) {
        if (is_array($having)) {
            foreach ($having as $key => $value) {
                $having[$key] = " $key = $value ";
            }
            $having = implode('AND', $having);
        }

        $this->_query['having'] .= $having;

        return $this;
    }

}
