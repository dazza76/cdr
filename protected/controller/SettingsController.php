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
    /**
     * Формирет страницу
     */
    public function index() {
        $this->content = $this->mainView('page/page-settings.php');
    }

}