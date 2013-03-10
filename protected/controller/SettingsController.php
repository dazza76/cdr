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
        $this->_section = $section;

        $action = 'section' . $section;
        $this->$action();

        $this->viewMain();
    }

    public function sectionOperator() {
        if ($_POST['action'] == 'add') {
            $this->actionOperatorAdd();
            App::refresh();
        }
        if ($_POST['action'] == 'delete') {
            $this->actionOperatorDelete();
            App::refresh();
        }
        if ($_POST['action'] == 'edit') {
            $this->actionOperatorEdit();
//            App::refresh();
        }

        if ($_GET['id']) {
            return $this->sectionOperatorEdit();
        }

        $this->queueAgent = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->query()
                ->getFetchObjects('QueueAgent');

        $this->view('page/settings/operator.php');
    }

    public function sectionOperatorEdit($id = null) {
        if ($id == null) {
            $id = $_GET['id'];
        }
        $id = (int) $id;

        $this->queueAgent = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->addWhere('agentid', $id)
                ->query()
                ->getFetchObjects('QueueAgent');

        $this->view('page/settings/operator_edit.php');
    }

    public function actionOperatorEdit($params = null) {
        Log::trace('actionOperatorEdit');
        if ($params == null) {
            $params = $_POST;
        }
        unset($params['action']);



        $agentid = (int) @$params['agentid'];
        if ( ! $agentid) {
            echo "no agentid";
        }
        $queueAgent = new QueueAgent($_POST);
        Log::vardump($queueAgent);

        $sets         = array();
        $sets['name'] = trim(@$params['name']);
        if ( ! $sets['name']) {
            echo "no name";
        }

        $sets['queues1']  = implode(',',
                                    ACPropertyValue::ensureFields($params['queues1']));
        $sets['penalty1'] = (int) @$params['penalty1'];

        $sets['queues2']  = implode(',',
                                    ACPropertyValue::ensureFields($params['queues2']));
        $sets['penalty2'] = (int) @$params['penalty2'];

        $sets['queues3']  = implode(',',
                                    ACPropertyValue::ensureFields($params['queues3']));
        $sets['penalty3'] = (int) @$params['penalty3'];

        App::Db()->createCommand()->update(QueueAgent::TABLE)
                ->set($sets)
                ->addWhere('agentid', $agentid)
                ->query();
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
        if ($params == null) {
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