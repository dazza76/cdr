<?php
/**
 * SettingsController class  - SettingsController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * SettingsController class
 *
 * @package		AC
 */
class SettingsController extends Controller {
    public $page        = "settings";

    public $section;

    /**
     * Формирет страницу
     */
    public function index() {
        $section = $_GET['section'];
        switch ($section ) {
            case 'operator':
            default :
                $section = 'operator';
                break;
        }
        $this->section = $section;
        $action = 'section'.$section;
        $this->$action();

        $this->viewMain();
    }


    public function sectionOperator() {
        Log::trace("Controller::sectionOperator()");

        $this->queueAgent = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->query()
                ->getFetchObjects('QueueAgent');

        $this->view('page/settings/operator.php');
    }

}