<?php
/**
 * User class  - User.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * User class
 * table users
 *
 * @property int        $id           id пользователя
 * @property bool       $exist        Существование
 * @property string     $sid          id сессии
 * @property DateTime   $online       online
 * @property string     $login        login
 * @property string     $pass         хеш пароля
 * @property array      $data         параметры пользователя
 *
 * @package     model
 */
class User extends Model
{

    const CLASS_NAME = __CLASS__;
    const TABLE      = "users";

    static protected $_rules = array(
        'id'     => 'id',
        'exist'  => 'bool',
        'sid'    => 'string',
        'online' => 'datetime',
        'login'  => 'string',
        'pass'   => 'string',
        'data'   => 'json',
    );

    public function rules($name = null)
    {
        return ($name !== null) ? self::$_rules[$name] : self::$_rules;
    }

    public function __construct($data = null)
    {
        parent::__construct($data);
        unset($this->pass);
    }

    public function getDeptId()
    {
        return (int) $this->data['deptid'];
    }
}