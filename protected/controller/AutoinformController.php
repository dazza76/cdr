<?php
/**
 * AutoinformController class  - AutoinformController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * AutoinformController class
 *
 * @package		AC
 */
class AutoinformController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function init($params = null) {
        parent::init($params);
        $this->index();
    }

    /**
     * Формирет страницу
     */
    public function index() {
        $this->viewMain('page/page-autoinform.php');
    }
}