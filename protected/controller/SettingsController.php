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

    public $page = "settings";
    public $section;

    /**
     * Формирет страницу
     */
    public function index() {
        $section = $_GET['section'];
        switch ($section) {
            case 'queue':
            case 'schedule':
                break;
            case 'operator':
            default :
                $section       = 'operator';
                break;
        }
        $this->section = $section;

        $action = 'section' . $section;
        $this->$action();

        $this->viewMain();
    }

    public function sectionOperator() {
        Log::trace("Controller::sectionOperator()");

        if($_POST['action'] == 'add') {
            $this->actionOperatorAdd();
            App::refresh();
        }
        if($_POST['action'] == 'delete') {
            $this->actionOperatorDelete();
            App::refresh();
        }

        $this->queueAgent = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->query()
                ->getFetchObjects('QueueAgent');

        $this->view('page/settings/operator.php');
    }

    public function actionOperatorAdd($params = null) {
        if ($params == null) {
            $params = $_POST;
        }
        unset($params['action']);

        $params['name'] = trim(@$params['name']);
        if ( ! $params['name']) {
            echo "no name";
        }
        $params['agentid'] = (int) @$params['agentid'];
        if ( ! $params['agentid']) {
            echo "no agentid";
        }


        $params['queues1'] = implode(',',
                                     ACPropertyValue::ensureFields($params['queues1']));
        if ( ! $params['queues1']) {
            unset($params['queues1']);
        }
        $params['penalty1'] = (int) @$params['penalty1'];
        if ( ! $params['penalty1']) {
            unset($params['penalty1']);
        }


        $params['queues2'] = implode(',',
                                     ACPropertyValue::ensureFields($params['queues2']));
        if ( ! $params['queues2']) {
            unset($params['queues2']);
        }
        $params['penalty2'] = (int) @$params['penalty2'];
        if ( ! $params['penalty2']) {
            unset($params['penalty2']);
        }


        $params['queues3'] = implode(',',
                                     ACPropertyValue::ensureFields($params['queues3']));
        if ( ! $params['queues3']) {
            unset($params['queues3']);
        }
        $params['penalty3'] = (int) @$params['penalty3'];
        if ( ! $params['penalty3']) {
            unset($params['penalty3']);
        }

        App::Db()->createCommand()->insert()
                ->into(QueueAgent::TABLE)
                ->values($params)
                ->query();

//        ac_dump($params);
    }


    public function actionOperatorDelete($params = null) {
        if($params == null) {
            $params = $_POST;
        }

        $agentid = (int) @$params['agentid'];

        App::Db()->createCommand()->delete()
                ->from(QueueAgent::TABLE)
                ->addWhere('agentid', $agentid)
                ->query();
    }

    public function sectionQueue() {

    }

    public function sectionSchedule() {

    }
}