<?php
/**
 * AutoinformController class  - AutoinformController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * AutoinformController class
 * Mar 12 2013 11:00:00:000AM
 * @package		AC
 */
class AutoinformController extends Controller {

    protected $_filters = array(
        'fromdate' => array('parseDatetime'),
        'todate'   => array('parseDatetime')
    );
    public $id;
    public $fromdate;
    public $todate;
    private $_result;

    public function __construct() {
        App::Config()->autoinform = @include APPPATH . 'config/autoinform.php';

        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function init($params = null) {
        parent::init($params);
        $this->index();

        $query = "SELECT * FROM autodialout ORDER BY datetotell;";

        if (App::Config()->autoinform['mssql']['enable']) {
            $db = mssql_connect(App::Config()->autoinform['mssql']['host'],
                                App::Config()->autoinform['mssql']['user'],
                                App::Config()->autoinform['mssql']['pass']); // OR DIE("Не могу создать соединение!");
            if ( ! $db) {
                Log::error("Не удаеться создать соединение с MSSQL!", 'SQL');
            }
            if ( ! mssql_select_db(App::Config()->autoinform['mssql']['dbname'],
                                   $db)) {
                Log::error("Не удаеться подключиться к базе MSSQL!", 'SQL');
            }

            $this->_result = mssql_query($query);
            if ( ! $this->_result) {
                Log::error("Не удаеться выполнить запрос MSSQL!", 'SQL');
            }
        } else {
            $this->_result = App::Db()->query($query);
        }



    }

    public function sectionList() {

    }

    public function sectionLog() {

    }

    public function fetchArray() {
        if ($this->_result instanceof ACDbResult) {
            return $this->_result->fetchArray();
        } else {
            return @mssql_fetch_array($this->_result);
        }
    }

    /**
     * Формирет страницу
     */
    public function index() {
        $this->viewMain('page/page-autoinform.php');
    }

    function parseDate($str) {
        switch (substr($str, 0, 3)) {
            case 'Jan':
                $mon = '01';
                break;
            case 'Feb':
                $mon = '02';
                break;
            case 'Mar':
                $mon = '03';
                break;
            case 'Apr':
                $mon = '04';
                break;
            case 'May':
                $mon = '05';
                break;
            case 'Jun':
                $mon = '06';
                break;
            case 'Jul':
                $mon = '07';
                break;
            case 'Aug':
                $mon = '08';
                break;
            case 'Sep':
                $mon = '09';
                break;
            case 'Oct':
                $mon = '10';
                break;
            case 'Nov':
                $mon = '11';
                break;
            case 'Dec':
                $mon = '12';
                break;
        };



        switch (substr($str, 24, 2)) {
            case 'PM':
                $add_hour = '12';
                if (substr($str, 12, 2) == 12)
                    $add_hour = '0';
                break;
            case 'AM':
                $add_hour = '0';
                if (substr($str, 12, 2) == 12)
                    $add_hour = '-12';
                break;
        };


        $date = substr($str, 4, 2) . ".$mon." . substr($str, 7, 4) . ' ' . (substr($str,
                                                                                   12,
                                                                                   2) + $add_hour) . ":" . (substr($str,
                                                                                                                   15,
                                                                                                                   2));

        return $date;
    }
}