<?php
/**
 * Session class
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   Copyright (c) 2013 AC Software
 */

/**
 * Session
 *
 * @package AC
 */
class ACSession
{

    private static $_session;

    public static function init($filename, $autosave = false)
    {
        $string = file_get_contents($filename);
        if ($string !== false) {
            self::$_session = @unserialize($string);
        }

        if ( ! is_array(self::$_session)) {
            self::$_session = array();
        }

        if ($autosave) {
            register_shutdown_function(array(__CLASS__, 'savefile'), $filename);
        }
    }

    public static function id()
    {
        if (self::$_session === null) {
            return session_id();
        } else {
            return 'file_tmp';
        }
    }

    public static function savefile($filename)
    {
        if (self::$_session !== null) {
            $string = @serialize(self::$_session);
        } else {
            $string = @serialize($_SESSION);
        }
        return file_put_contents($filename, $string);
    }

    public static function start($filename = null, $autosave = false)
    {
        log::trace('session start');
        if (func_num_args() == 0) {
            if ( ! session_id()) {
                return session_start();
            }
            return true;
        }

        self::$_session = @unserialize(file_get_contents($filename));
        if ( ! is_array(self::$_session)) {
            self::$_session = array();
        }

        if ($autosave) {
            register_shutdown_function(array(__CLASS__, 'savefile'), $filename);
        }

        return true;
    }

    public static function get($name)
    {
        if (self::$_session === null) {
            return $_SESSION[$name];
        } else {
            return self::$_session[$name];
        }
    }

    public static function set($name, $value)
    {
        if (self::$_session === null) {
            $_SESSION[$name] = $value;
        } else {
            self::$_session[$name] = $value;
        }
    }
}
