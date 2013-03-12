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

    protected $_filters  = array(
        'fromdate'  => array('parseDatetime'), // array('_parseDatetime'),
        'todate'    => array('parseDatetime'),
        'oper'      => 1,
        'src'       => array('parsePhone'),
        'dst'       => array('parsePhone'),
        'coming'    => 1,
        'fileExist' => 1,
        'comment'   => 1,
        'limit'     => 1,
        'offset'    => 1,
        'sort'      => array('parseSort', array(
                "calldate",
                "src",
                "dst",
                "duration",
                "comment",
            )),
        'mob'       => array('parseCheck'),
        'desc'      => 1
    );
    protected $_sections = array(
        'calls'     => 'Звонки',
        'answering' => 'Автоинформатор',
    );

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

    public function __construct() {
        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));

        $this->_filters['fromdate'][1] = $from;
    }

    /**
     * Автоматическая инициализация
     * @param array $params
     */
    public function init($params = null) {
        parent::init($params);

        if ($this->_actType === self::TYPE_ACTION) {
            $action = "action" . $this->_atcion;
            if ( ! method_exists($this, $action)) {
                $this->content = $action . "  error action";
                return;
            }
        } else {
            $action = 'index';
        }

        $this->$action();
    }

    /**
     * Формирет страницу
     */
    public function index() {
        while ($this->getCountFileExists()) {
            $this->actionCheckFile();
        }

        $search = 'search' . $this->getSection();
        $this->$search();

        // Подключаем файлы JS и CSS
        $this->dataPage['links'] .= '<script src="' . Utils::linkUrl('lib/player/jquery.jplayer.min.js') . "\"></script>\n"
                . ' <link rel="stylesheet" href="' . Utils::linkUrl('lib/player/jplayer.blue.monday.css') . "\">\n";

        $this->viewMain("page/cdr/{$this->_section}.php");
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

        $id      = FiltersValue::parseId($params['id']);
        $comment = FiltersValue::parseComment($params['comment']);

        App::Db()->createCommand()->update(Cdr::TABLE)
                ->addSet('comment', $comment, true)
                ->addWhere('id', $id)
                ->query();

        $this->content = 1;

        return App::Db()->success;
    }

    /**
     * Проверяет наличия файлов аудиозаписей и редактирует записи в таблицы.
     * Проверка проходитпорциями
     * @param int $limit_scan количество сканируемых файлов за раз
     * @return array результат работы [count_yes_1, count_file_yes_2, count__no]
     */
    public function actionCheckFile($limit_scan = 500) {
        if ($limit_scan <= 0) {
            $limit_scan = 100;
        }
        $DB         = App::Db();

        $command = $DB->createCommand()->select('id, calldate, uniqueid')
                ->from(Cdr::TABLE)
                ->where(' `file_exists` IS NULL ')
                ->limit($limit_scan);

        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }

        $rows = $command->query()->getFetchAssocs();

        // $dir        = Cdr::monitorDir(); // $_SERVER['DOCUMENT_ROOT'] . App::Config()->cdr->monitor_dir . "/";
        $file_yes_1 = array();
        $file_yes_2 = array();
        $file_no    = array();
        foreach ($rows as $row) {
            $file = $_SERVER['DOCUMENT_ROOT'] . Cdr::monitorFile($row['uniqueid']);
            if (file_exists($file)) {
                $file_yes_1[] = $row['id'];
                continue;
            }

            $file = $_SERVER['DOCUMENT_ROOT'] . Cdr::monitorFile($row['uniqueid'],
                                                                 $row['calldate']);
            if (file_exists($file)) {
                $file_yes_2[] = $row['id'];
                continue;
            }

            $file_no[] = $row['id'];
        }

        if (count($file_yes_1)) {
            App::Db()->createCommand()->update(Cdr::TABLE)
                    ->addSet('`file_exists`', "1")
                    ->addWhere('id', $file_yes_1, 'IN')
                    ->query();
        }
        if (count($file_yes_2)) {
            App::Db()->createCommand()->update(Cdr::TABLE)
                    ->addSet('`file_exists`', "2")
                    ->addWhere('id', $file_yes_2, 'IN')
                    ->query();
        }
        if (count($file_no)) {
            App::Db()->createCommand()->update(Cdr::TABLE)
                    ->addSet('`file_exists`', "0")
                    ->addWhere('id', $file_no, 'IN')
                    ->query();
        }

        return array(count($file_yes_1), count($file_yes_2), count($file_no));
    }

    /**
     * Выполнить выборгу по звонкам.
     * Поиск записей по заданому фильтру контролера
     * @return bool
     */
    public function searchCalls() {
        $DB   = App::Db();
        $sort = $this->sort;
        if ($this->desc) {
            $sort .= " DESC ";
        }
        $command = $DB->createCommand()->select()
                ->from(Cdr::TABLE)
                ->calc()
                ->addWhere('file_exists', '0', '>')
                ->limit($this->limit)
                ->offset($this->offset)
                ->order($sort);

        // начальная дата
        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        // конечная дата
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }
        // оператор
        if ($this->oper) {
            $oper = $DB->escapeString($this->oper);
            $command->where(
                    " AND ("
                    . "(`dcontext` = 'incoming' AND `dstchannel` = '$oper')"
                    . "OR  (`dcontext` <> 'incoming' AND `userfield`= '$oper' )"
                    . ") ");
        }
        // поиск по входящим номерам
        if ($this->src) {
            $command->addWhere('src', "%{$this->src}%", 'LIKE');
        }
        // поиск по исходящим номерам (или наоборот, не помню)
        if ($this->dst) {
            $command->addWhere('dst', "%{$this->dst}%", 'LIKE');
        }
        // тип звонка входящий, исходящий или все
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
        // коментарий
        if ($this->comment) {
            $command->addWhere('comment', "%{$this->comment}%", 'LIKE');
        }
        // только мобильные
        if ($this->mob) {
            // "9ХХХХХХХХХ" и исходящие вида
            // "[9]89XXXXXXXXX".
            $command->where(" AND ("
                    . "(LEFT(`src`, 1)='9' AND CHAR_LENGTH(`src`)=10)"
                    . "OR (LEFT(`dst`, 3)='989' AND CHAR_LENGTH(`dst`)=12)"
                    . ")");
        }

        $result       = $command->query();
        $this->offset = $result->calc['offset'];
        $this->limit  = $result->calc['limit'];
        $this->count  = $result->calc['count'];
        $this->rows   = $result->getFetchObjects('Cdr');

        return ($result->count()) ? true : false;
    }

    /**
     * Выполнить выборгу по Автоинформаторам.
     * Поиск записей по заданому фильтру контролера
     * @return bool
     */
    public function searchAnswering() {
        $DB   = App::Db();
        $sort = $this->sort;
        if ($this->desc) {
            $sort .= " DESC ";
        }
        $command = $DB->createCommand()->select()
                ->from(Cdr::TABLE)
                ->calc()
                ->addWhere('file_exists', '0', '>')
                ->addWhere('dcontext',
                           array('autoinform', 'outgoing', 'dialout'), 'IN')
                ->limit($this->limit)
                ->offset($this->offset)
                ->order($sort);


        // начальная дата
        if ($this->fromdate) {
            $command->addWhere('calldate', $this->fromdate->format(), '>=');
        }
        // конечная дата
        if ($this->todate) {
            $command->addWhere('calldate', $this->todate->format(), '<=');
        }
        // поиск по входящим номерам
        if ($this->src) {
            $command->addWhere('src', "%{$this->src}%", 'LIKE');
        }
        // коментарий
        if ($this->comment) {
            $command->addWhere('comment', "%{$this->comment}%", 'LIKE');
        }
        // только мобильные
        if ($this->mob) {
            // "9ХХХХХХХХХ" и исходящие вида
            // "[9]89XXXXXXXXX".
            $command->where(" AND ("
                    . "(LEFT(`src`, 1)='9' AND CHAR_LENGTH(`src`)=10)"
                    . "OR (LEFT(`dst`, 3)='989' AND CHAR_LENGTH(`dst`)=12)"
                    . ")");
        }

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