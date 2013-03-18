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

    public function init($params = null) {
        parent::init($params);
        $this->index();
    }

    /**
     * Формирет страницу
     */
    public function index() {
        $section        = $this->_ensureSection($_GET['section']);
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
            App::location($this->getPage(),
                          array('section' => $this->getSection()));
        }

        if ($_GET['id']) {
            return $this->sectionOperatorEdit();
        }

        $this->queueAgent = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->order('name')
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

    public function sectionQueue() {
        if (in_array($_POST['action'], array('add', 'delete', 'edit'))) {
            $this->actionQueue($_POST);
            App::location($this->getPage(),
                          array('section' => $this->getSection(), 'r'       => rand()));
        }

        if ($_GET['uniqueid']) {
            $uniqueid         = $_GET['uniqueid'];
            $result           = App::Db()->createCommand()->select()
                    ->from('queue_member_table')
                    ->addWhere('uniqueid', $uniqueid)
                    ->query();
            $this->dataQueues = $result; //->getFetchAssocs();

            $this->view('page/settings/queue_edit.php');
            return;
        }

        $result           = App::Db()->createCommand()->select()
                ->from('queue_member_table')
                ->query(); //->getFetchAssocs();
        $this->dataQueues = $result;

        $this->view('page/settings/queue.php');
    }

    public function sectionMode() {
        
    }

    public function sectionAnswering() {
        
    }

    public function actionQueue($params = null) {
        if ($params == null) {
            $params = $_POST;
        }
        $action = $params['action'];
        unset($params['action']);


        if ($action == 'delete') {
            $uniqueid = trim($params['uniqueid']);
            App::Db()->createCommand()->delete()->from('queue_member_table')
                    ->addWhere('uniqueid', $uniqueid)
                    ->limit(1)
                    ->query();
            return 1;
        }


        if ($action == 'add' || $action == 'edit') {
            $values               = array();
            $values['queue_name'] = trim($params['queue_name']);
            $values['interface']  = trim($params['interface_1']) . "(SIP/" . trim($params['interface_1']) . ")";
            $values['penalty']    = (int) $params['penalty'];
            $values['uniqueid']   = trim($params['uniqueid']);
            $values['paused']     = (int) $params['paused'];

            if ($action == 'add') {
                App::Db()->createCommand()->insert()->into('queue_member_table')
                        ->ignore()
                        ->values($values)
                        ->query();
            } else {
                $uniqueid           = $values['uniqueid'];
                $values['uniqueid'] = trim($params['uniqueid_new']);
                App::Db()->createCommand()->update('queue_member_table')
                        ->set($values)
                        ->addWhere('uniqueid', $uniqueid)
                        ->ignore()
                        ->query();
            }
            return 1;
        }

        return 0;
    }

    public function sectionSchedule() {
        
    }

    public function sectionPause() {
        
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
        Log::dump($queueAgent, 'queueAgent');

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
}