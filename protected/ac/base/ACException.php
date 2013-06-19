<?php
/**
 * ACException class  - ACException.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */


/**
 * ACException class
 *
 * @package		AC
 */
class ACException extends Exception {
}

/**
 * ACSingletonException class
 *
 * @package		AC
 */
class ACSingletonException extends Exception {
    public function __construct($message = 'none') {
        parent::__construct("Объект класса - Singleton(Одиночка) уже создан {$message}.");
    }
}
