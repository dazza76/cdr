<?php
/**
 * CdrController class  - CdrController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * CdrController class
 *
 * @property ACDateTime $fromdate -
 * @property ACDateTime $todate   -
 * @property string     $oper         -
 * @property string     $src          -
 * @property string     $dst          -
 * @property string     $coming       -
 * @property bool       $fileExist    -
 * @property string     $comment      -
 * @property int        $limit        -
 * @property int        $offset       -
 * @property string     $sort
 * @property int        $desc
 */
class CdrController extends Controller {

    protected $_sortColumn = array(
        "calldate",
        "src",
        "dst",
        "duration",
        "comment",
    );
    protected $_filters    = array(
        'fromdate'  => array('_parseDatetime'),
        'todate'    => array('_parseDatetime'),
        'oper'      => 1,
        'src'       => array('_parsePhone'),
        'dst'       => array('_parsePhone'),
        'coming'    => 1,
        'fileExist' => 1,
        'comment'   => 1,
        'limit'     => 1,
        'offset'    => 1,
        'sort'      => array('_parseSort'),
        'desc'      => 1
    );
    public $page        = "cdr";

    /**
     * @var int
     */
    public $count;

    /**
     * @var int
     */
    public $countFileExists;

    /**
     * @var array
     */
    public $rows = array();

    // --------------------------------------------------------------

    function __construct() {
        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    public function init($params = null) {
        // if ($params === null) {
        //     $params = array();
        //     foreach ($this->_filters as $key => $value) {
        //         if ( ! empty($_GET[$key])) {
        //             $params[$key] = $_GET[$key];
        //         }
        //     }
        //     if (count($params)) {
        //         $_SESSION['pg_cdr'] =$params;
        //     } else {
        //         $this->_initGetParams = true;
        //         $params = $_SESSION['pg_cdr'];
        //     }
        // }
       if ($params === null) {
           if (!count($_GET)) {
               $params =  $_SESSION['pg_cdr'];
               $this->_sessionParams = true;
           } else {
              $params = $_GET;
           }
       }
       $_SESSION['pg_cdr'] = $params;

        Log::trace('Session parametr: '.((int) $this->_sessionParams));
        Log::vardump($params);
        parent::init($params);
    }

    /**
     * Формирет страницу
     */
    public function index() {
        while ($this->getCountFileExists()) {
            $this->actionCheckFile();
        }

        $this->search();

        $this->_addJsSrc('player/jquery.jplayer.min.js');
        $this->_addCssLink('../js/player/jplayer.blue.monday.css');
        $this->viewMain('page/page-cdr.php');
    }

    /**
     * Редактировать коментарий
     * @param array $params
     * @return bool
     */
    public function actionEditComment($params = null) {
        if ( ! is_array($params)) {
            $params = $_POST;
        }

        $id      = $this->_parseId($params['id']);
        $comment = $this->_parseComment($params['comment']);

        App::Db()->createCommand()->update(Cdr::TABLE)
                ->addSet('comment', $comment, true)
                ->addWhere('id', $id)
                ->query();

        $this->content = 1;

        return App::Db()->success;
    }

    /**
     *
     */
    public function actionCheckFile() {
        $DB = App::Db();

        $command = $DB->createCommand()->select('id, uniqueid')
                ->from(Cdr::TABLE)
                ->where(' `file_exists` IS NULL ')
                ->limit(500);

        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }

        $rows = $command->query()->getFetchAssocs();

        $dir      = $_SERVER['DOCUMENT_ROOT'] . App::Config()->cdr->monitor_dir . "/";
        $file_yes = array();
        $file_no  = array();
        foreach ($rows as $row) {
            $file = $dir . $row['uniqueid'] . '.' . App::Config()->cdr->file_format;
            if (file_exists($file)) {
                $file_yes[] = $row['id'];
            } else {
                $file_no[] = $row['id'];
            }
        }

        if (count($file_yes)) {
            App::Db()->createCommand()->update(Cdr::TABLE)
                    ->addSet('`file_exists`', "1")
                    ->addWhere('id', $file_yes, 'IN')
                    ->query();
        }
        if (count($file_no)) {
            App::Db()->createCommand()->update(Cdr::TABLE)
                    ->addSet('`file_exists`', "0")
                    ->addWhere('id', $file_no, 'IN')
                    ->query();
        }
    }

    /**
     * Выполнить выборгу
     * @return bool
     */
    public function search() {
        $DB   = App::Db();
        $sort = $this->sort;
        if ($this->desc) {
            $sort .= " DESC ";
        }
        $command = $DB->createCommand()->select()
                ->from(Cdr::TABLE)
                ->calc()
                ->limit($this->limit)
                ->offset($this->offset)
                ->order($sort);

        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }
        if ($this->oper) {
            $oper = $DB->escapeString($this->oper);
            $command->where(
                    " AND ("
                    . "(`dcontext` = 'incoming' AND `dstchannel` = '$oper')"
                    . "OR  (`dcontext` <> 'incoming' AND `userfield`= '$oper' )"
                    . ") ");
        }
        if ($this->src) {
            $command->addWhere('src', "%{$this->src}%", 'LIKE');
        }
        if ($this->dst) {
            $command->addWhere('dst', "%{$this->dst}%", 'LIKE');
        }
        if ($this->coming) {
            if ($this->coming == Cdr::INCOMING) {
                $command->addWhere('dcontext', 'incoming');
            }
            if ($this->coming == Cdr::OUTCOMING) {
                $command->where(
                        " AND ("
                        . "(LEFT(`dcontext`, 4)='from' AND CHAR_LENGTH(`dst`)>4)"
                        . "OR (LEFT(`dcontext`, 4)<>'from' AND `dcontext`<>'incoming')"
                        . ")");
            }
        } else {
            $command->where(" AND NOT (  LEFT(`dcontext`, 4)='from' AND CHAR_LENGTH(`dst`)<=4  ) ");
        }

        if ($this->comment) {
            $command->addWhere('comment', "%{$this->comment}%", 'LIKE');
        }

        $command->addWhere('file_exists', '1');

        $result       = $command->query();
        $this->offset = $result->calc['offset'];
        $this->limit  = $result->calc['limit'];
        $this->count  = $result->calc['count'];
        $this->rows   = $result->getFetchObjects('Cdr');

        return ($result->count()) ? true : false;
    }

    /**
     * Возвращает количество неотсканеных записей
     * @return int
     */
    public function getCountFileExists() {
        $DB = App::Db();

        $command = $DB->createCommand()->select('COUNT(id) AS total')
                ->from(Cdr::TABLE)
                ->where(' `file_exists` IS NULL ');
        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }

        $arr = $command->query()->fetch();
        return $arr['total'];
    }
}