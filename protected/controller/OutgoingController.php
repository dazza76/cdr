<?php

/**
 * OutgoingController class  - OutgoingController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * OutgoingController class
 */
class OutgoingController extends Controller {

    protected $_filters = array(
    );

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
        $this->viewMain();
    }
}