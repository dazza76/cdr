<?php
/**
 * ACDbCommand class  - ACDbCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */


/**
 * ACDbCommand class
 *
 * @package		AC.db.command
 */
class ACDbCommand {

    private $_dbConnection;

    public function __construct(ACDbConnection $dbConnection) {
        $this->_dbConnection = $dbConnection;
    }

    /**
     * Блокировка таблиц.
     * LOCK TABLES tbl_name READ
     * @param array|string $tables - таблицы
     * @return ACDbLockCommand
     */
    public function lock($tables) {
        return new ACDbLockCommand($this->_dbConnection, $tables);
    }

    /**
     * Разблоеировка таблиц.
     * @return ACDbUnlockCommand
     */
    public function unlock() {
        return new ACDbUnlockCommand($this->_dbConnection);
    }

    /**
     * Вставка записи в табоицу.
     * Значения не экранируються!
     * @return ACDbInsertCommand
     */
    public function insert() {
        return new ACDbInsertCommand($this->_dbConnection);
    }

    /**
     * @return ACDbDeleteCommand
     */
    public function delete() {
        return new ACDbDeleteCommand($this->_dbConnection);
    }

    /**
     * @param string $table - таблица обновления
     * @return ACDbUpdateCommand
     */
    public function update($table) {
        return new ACDbUpdateCommand($this->_dbConnection, $table);
    }

    /**
     * Выборга данных
     * @param array|string $select - столбцы таблицы выборги
     * @return ACDbSelectCommand
     */
    public function select($select = '*') {
        return new ACDbSelectCommand($this->_dbConnection, $select);
    }
}