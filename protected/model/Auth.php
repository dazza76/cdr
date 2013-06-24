<?php
/**
 * Auth class  - Auth.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * Auth class
 *
 * @package		App
 */
class Auth
{

    private $_secret;

    /** @var User    */
    public $user;

    /**
     *
     * @param string $secret
     */
    public function __construct($secret)
    {
        $this->_secret = $secret;
    }

    /**
     * Вход в систему через POST
     * @return boolean
     */
    public function login()
    {
        $login = $_POST['slogin'];
        $pass  = $_POST['spass'];

        $this->user = App::Db()->createCommand()->select()->from("users")
                ->addWhere("login", $login)
                ->addWhere("pass", $this->hex($pass))
                ->query()
                ->fetchObject(User);

        if ( ! $this->user) {
            $this->user = $this->_defaultUser();
            return false;
        }
        $this->user->sid = ACSession::id();
        // $this->_timestamp();
        // $this->log('login', ACSession::id());
        ACSession::set('user', $this->user);

        return true;
    }

    /**
     * Выход из системы
     */
    public function logout()
    {
        ACSession::set('user', null);
        if ($this->user->id) {
            App::Db()->createCommand()->update('users')
                    ->addSet('sid', '')
                    ->addWhere('id', $this->user->id)
                    ->query();
        }
        $this->user = $this->_defaultUser();


        ACSession::set('user', $this->user);
    }

    /**
     *
     * @return boolean
     */
    public function authorization()
    {
        $user = ACSession::get('user');
        if ( ! ($user instanceof User)) {
            $this->user = $this->_defaultUser();
            return false;
        }

        $this->user = $user;

        // $dt = time() - strtotime($this->user->online);
        // if ($dt > 300) {
        //     $this->_timestamp();
        // }

        return true;
    }

    /**
     *
     * @return bool
     */
    public function isAuth()
    {
        return ($this->user->id) ? true : false;
    }

    /**
     * Логирование действия пользователя
     *
     * @param string $action название действия
     * @param string $data   параметры
     */
    // public function log($action, $data)
    // {
    //     App::Db()->createCommand()->insert()->into("users_log")
    //             ->addValue('login', $this->user->login)
    //             ->addValue('ip', ACUtils::getIp())
    //             ->addValue('action', $action)
    //             ->addValue('data', $data)
    //             ->query();
    // }

    /**
     * Дополнительная усложнения пароля
     *
     * @param string $string
     * @return string
     */
    public function hex($string)
    {
        $string .= $this->_secret;
        return md5($string);
    }

    private function _defaultUser()
    {
        $user     = new User(0);
        return $user;
    }

    // private function _timestamp()
    // {
    //     App::Db()->createCommand()->update('users')
    //             ->addSet('sid', $this->user->sid)
    //             ->addWhere('id', $this->user->id)
    //             ->query();
    //     $this->user->online = date('Y-m-d H:i:s');
    //     ACSession::set('user', $this->user);
    // }
}