<?php
/**
 * ACDbInsertCommand class  - ACDbInsertCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * Вставка записи в табоицу.
 * INSERT [IGNORE] [INTO] tbl_name [(col_name,...)]
 * VALUES (expression,...),(...),...
 *
 */
class ACDbInsertCommand extends ACDbBaseCommand {

    protected $_command = 'INSERT';
    private $_values = array();
    private $_filter = null;

    public function __construct(ACDbConnection $dbConnection) {
        parent::__construct($dbConnection);
    }

    public function toString() {
        if($this->_filter)  {
            $params= array();
            foreach($this->_filter as $colum) {
                $params[$colum] = $this->_values[$colum];
            }
        } else {
        $params = $this->_values;
    }

        $keys = implode(", ", array_keys($params));
        $vals = implode(", ", array_values($params));
        $values = " ( {$keys} ) VALUES ( {$vals} )";

        return "INSERT "
                . $this->_query["ignore"]
                . $this->_query["into"]
                . $values;
    }

    /**
     * Если указывается ключевое слово IGNORE, то команда обновления не будет
     * прервана, даже если при обновлении возникнет ошибка дублирования ключей.
     * Строки, из-за которых возникают конфликтные ситуации, обновлены не будут.
     *
     * @return self
     */
    public function ignore() {
        $this->_query['ignore'] = ' IGNORE ';
        return $this;
    }

    /**
     * Таблица
     * @param string $table
     * @return self
     */
    public function into($table) {
        $this->_query["into"] = "INTO " . $table . " ";
        return $this;
    }

    /**
     * Вставляет строки в соответствии с точно указанными в команде значениями.
     * При передачи параметра масивом, формат <ключ:значение>.
     * Значения не экранируються!
     *
     * Входной масив данных:
     * $array = (
     *   "column_1" => "param_1",
     *   "column_2" => "param_2"
     * )
     *
     * @param array   $values  -  масив
     * @param boolean $escape - нужно ли экранировать параметры (только при передачи масива)
     * @return self
     */
    public function values(array $values, $escape = true) {
            if ($escape)
                array_walk($values, array($this->_dbConnection, 'quoteEscapeString'));
            $this->_values = array_merge($this->_values, $values);
            return $this;
    }

    /**
     * Ограничить вставляемые поля. Применяет к запросу только те поля,
     * что были здесь установлены
     * @param array $columns
     * @return self
     */
    public function filterColumn(array $columns) {
        $this->_filter = $columns;
    }

    public function addValue($column, $value, $quoteEscape = true) {
        if($quoteEscape)
            $value = $this->_dbConnection->quoteEscapeString($value);
        $this->_values[$column] = $value;
        return $this;
    }
}
