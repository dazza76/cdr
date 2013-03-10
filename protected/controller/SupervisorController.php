<?php
/**
 * SupervisorController class  - SupervisorController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * SupervisorController class
 *
 * @package		AC
 */
class SupervisorController extends Controller {

    public function index() {
        $this->viewMain('page/supervisor/supervisor_queue.php');
    }
}