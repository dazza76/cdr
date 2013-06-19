<?php
/**
 * ACDbDeleteCommand class  - ACDbDeleteCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACDbBaseCommand class.
 *
 * Удаление записи из таблици
 *
 * DELETE [LOW_PRIORITY | QUICK] table_name[.*] [,table_name[.*] ...]
 * FROM table-references
 * [WHERE where_definition]
 *
 */
class ACDbDeleteCommand extends ACDbWhereCommand {
    protected $_command = 'DELETE';

    /**
     * Оператор DELETE удаляет из таблицы tables строки, удовлетворяющие заданным
     * в where условиям, и возвращает число удаленных записей.
     * @param string|array $tables
     *
     */
    public function __construct(ACDbConnection $dbConnection) {
        parent::__construct($dbConnection);
    }


    /**
     * @param string $table таблицы
     * @return self
     */
    public function from($table) {
        $this->_query['from'] = (string) $table;
        return $this;
    }


}
