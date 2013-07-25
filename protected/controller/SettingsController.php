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

        // if ( headers_sent()) Log::error("Header> headers was sent", 'Controller'); // <<<<<<< lg

        $this->$action();

        if ($this->_actType === self::TYPE_PAGE) {
            $this->viewMain();
        }
    }
    // ---------------------------------------------
    // ACTION
    // ---------------------------------------------

    /**
     * Операторы
     */
    public function sectionOperator($id = null) {
        if ($_POST['action'] == 'add') {
            $this->actionOperatorAdd();
            App::refresh();
            //exit();
        }
        if ($_POST['action'] == 'delete') {
            $this->actionOperatorDelete();
            App::refresh();
            //exit();
        }
        if ($_POST['action'] == 'edit') {
            $this->actionOperatorEdit();
            App::location($this->getPage(),
                          array('section' => $this->getSection()));
            //exit();
        }

        if ($id == null) {
            $id = $_GET['id'];
        }
        $id = (int) $id;

        if ($id > 0) {
            $this->queueAgent = App::Db()->createCommand()
                    ->select()
                    ->from(QueueAgent::TABLE)
                    ->addWhere('agentid', $id)
                    ->query()
                    ->getFetchObjects('QueueAgent');
            $this->view('page/settings/operator_edit.php');
            return;
        }

        $queue = FiltersValue::parseQueue($this->queue);
        Log::dump($queue, "queue");


        $command = App::Db()->createCommand()
                ->select()
                ->from(QueueAgent::TABLE)
                ->calc()
                ->order('name');
        if ($this->fio) {
            $command->addWhere('name', "%{$this->fio}%", 'LIKE');
        }
        if ($this->agent) {
            $command->addWhere('agentid', "%{$this->agent}%", 'LIKE');
        }

        $command->limit(20);
        if ($this->offset) {
            $command->offset($this->offset);
        }


        $result           = $command->query();
        $this->count      = $result->foundRows;
        $this->queueAgent = $result->getFetchObjects('QueueAgent');

        $this->view('page/settings/operator.php');
    }

    /**
     * Очереди
     */
    public function sectionQueue() {
        // Action: create
        if ($_POST['action'] == 'create') {
            $fields = $_POST;
            unset($fields['action']);

            log::dump($fields, 'fields');

            App::Db()->createCommand()->insert()->into('queue_table')->ignore()
                    ->values($fields)
                    ->query();

            if (App::Db()->success) {
                App::location($this->getPage(),
                              array('section' => $this->getSection()));
                App::Response()->send();
                exit();
            }
            return;
        }

        // Action: delete
        if ($_POST['action'] == 'delete') {
            $name = (string) @$_POST['name'];

            App::Db()->createCommand()->delete()
                    ->from('queue_table')
                    ->addWhere('name', $name)
                    ->limit(1)
                    ->query();
            App::refresh();
            exit();
        }

        if ($_POST['action'] == 'edit') {
            $fields = $_POST;
            $name = $fields['name'];
            unset($fields['action'], $fields['name']);


            log::dump($fields, 'fields');

            App::Db()->createCommand()->update('queue_table')
                    ->set($fields)
                    ->addWhere('name', $name)
                    ->query();

            if (App::Db()->success) {
                App::refresh();
                exit();
            } else {
                echo "Update error";
            }
            return;
        }


        // View: edit
        if ($_GET['name']) {
            $name             = (string) $_GET['name'];
            $this->dataQueues = App::Db()->createCommand()->select()->from('queue_table')
                    ->addWhere('name', $name)
                    ->query()
                    ->fetch();

            $this->view('page/settings/queue_edit.php');
            return;
        }

        // View: create
        if ($_GET['tab'] == 'create') {
            $this->view('page/settings/queue_edit.php');
            return;
        }

        // View: list
        $result = App::Db()->createCommand()->select()->from('queue_table')
                ->limit(20)
                ->query();

        $this->dataQueues = $result;
        $this->view('page/settings/queue.php');
    }

    /**
     * Расписание
     */
    public function sectionSchedule() {
        if ($this->_actType === self::TYPE_ACTION) {
            return $this->actionSchedule($_POST);
        }

        $this->date   = FiltersValue::parseDatetime($_GET['date']);
        // $this->agents = QueueAgent::getQueueAgents();


        if (!empty($_COOKIE['schedule_agentid'])) {
            App::Config()->setting_schedule['agentid'] = explode(",", $_COOKIE['schedule_agentid']);
        } else {
            App::Config()->setting_schedule['agentid'] = array_keys( QueueAgent::getQueueAgents() );
        }
        LOG::dump(App::Config()->setting_schedule, 'App::Config()->setting_schedule'); // LOG::dump


        $this->schedule = array();
        $result         = App::Db()->createCommand()->select()->from('timetable')
                ->where("`agentid_day` LIKE '%" . $this->date->format('Y-m') . "%'")
                ->order('`agentid_day`')
                ->query();
        foreach ($result as $row) {
            list($id, $day) = explode(" ", $row['agentid_day']);
            // TODO разделитель в таблице timetable
            if ( ! $id) {
                list($id, $day) = explode(".", $row['agentid_day']);
            }
            $id  = (int) $id;
            $day = (int) substr($day, 8);
            unset($row['agentid_day']);

            $this->schedule[$id][$day] = $row;
        }
        Log::dump($this->schedule, 'schedule');

        $this->_addJsSrc('schedule.js');
        $this->view('page/settings/schedule.php');
    }

    /**
     * Режим работы
     */
    public function sectionMode() {

    }

    /**
     * Паузы
     */
    public function sectionPause() {

    }

    /**
     * Автоинформаторы
     */
    public function sectionAnswering() {
        App::Config('autoinform'); //->autoinform = @include APPPATH . 'config/autoinform.php';
        // Log::dump(App::Config());

        $filename = App::Config()->autoinform['file_conf'];
        if ( ! file_exists($filename)) {
            // print_r(App::Config()->autoinform);
            // Log::trace("Конфигурационный файл не найден");
            die("1 : Конфигурационный файл не найден");
        }


        $this->options = array();
        foreach (file($filename) as $row) {
            list($key, $value) = explode("=", $row);
            $this->options[$key] = trim($value);
        }
        Log::dump($this->options, 'File(parse)');


        if ($_POST['action'] == 'edit') {
            // $this->actionOperatorEdit();
            // App::location($this->getPage(), array('section' => $this->getSection()));
            // exit();
            $options = $_POST;
            unset($options['action']);

            $this->options = array_merge($this->options, $options);
            Log::dump($this->options, 'merge');

            $context = "";
            foreach ($this->options as $key => $value) {
                $context .= $key . "=" . trim($value) . PHP_EOL;
            }

            if (file_put_contents($filename, $context) === false) {
                die("Не удалось сохранить");
            }
            // App::location($this->getPage(), array('section' => $this->getSection()));
            App::refresh();
            // exit();
        }



        $this->view('page/settings/answering.php');
    }


    public function sectionInvalidevents() {

    }

    // ---------------------------------------------
    // ACTION
    // ---------------------------------------------


    /*
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


     */


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

    public function actionSchedule($params = null) {
        if ($params == null) {
            $params = $_POST;
        }


        $aid   = (int) $params['agentid'];
        $date  = new ACDateTime(ACPropertyValue::ensureDate($params['date']));
        $event = App::Db()->escapeString($params['event']);
        $start = ACPropertyValue::ensureTime(App::Db()->escapeString($params['time_h'] . ":" . $params['time_m'] . ":00"));


        $duration = App::Db()->escapeString((int) $params['duration']);
        $days     = (int) $params['days'];
        if ($days <= 0)
            $days     = 1;
        $result   = array();
        for ($i = 1; $i <= $days; $i ++ ) {
            $agentid_day = $aid . " " . $date->format('Y-m-d');
            switch ($event) {
                case 'off':
                    $query = "DELETE FROM timetable WHERE agentid_day='{$agentid_day}' LIMIT 1";
                    break;
                case 'vac':
                case 'ill':
                    $query = "REPLACE INTO timetable (agentid_day, event, start, duration) VALUES ('{$agentid_day}', '{$event}', NULL, 0) ";
                    break;
                case 'job':
                    $query = "REPLACE INTO timetable (agentid_day, event, start, duration) VALUES ('{$agentid_day}', '{$event}', '$start', '$duration') ";
                    break;
                default:
                    $query = null;
                    break;
            }
            $date->add(new DateInterval("P1D"));

            App::Db()->query($query);
            if (App::Db()->success) {
                $result[] = $query;
            }
        }


        $this->content = ACJSON::encode(array("response" => $result));
    }
}