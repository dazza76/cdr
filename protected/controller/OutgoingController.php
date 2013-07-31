<?php

/**
 * OutgoingController class  - OutgoingController.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * OutgoingController class
 */
class OutgoingController extends Controller {

    protected $_filters = array(
        'fromdate' => array('parseDatetime'), // array('_parseDatetime'),
        'todate' => array('parseDatetime'),
        'src' => array('parsePhone'),
        'dst' => array('parsePhone'),
        'oper' => 1,
        'limit' => 1,
        'offset' => 1,
        'desc' => 1
    );

    public function __construct() {
        parent::__construct();

        $from = new ACDateTime();
        $from->sub(new DateInterval('P1D'));
        $this->_filters['fromdate'][1] = $from;
    }

    public function init($params = null) {
        parent::init($params);
            $this->index();
    }

    /**
     * Формирет страницу
     */
    public function index() {

        $fromdate=$_GET["fromdate"]." ".$_GET["fromhour"].":".$_GET["frommin"];
        $todate=$_GET["todate"]." ".$_GET["tohour"].":".$_GET["tomin"];

        $tempfromdate=substr($_GET["fromdate"],6,4)."-".substr($_GET["fromdate"],3,2)."-".substr($_GET["fromdate"],0,2)." ".$_GET["fromhour"].":".$_GET["frommin"];
        $temptodate=substr($_GET["todate"],6,4)."-".substr($_GET["todate"],3,2)."-".substr($_GET["todate"],0,2)." ".$_GET["tohour"].":".$_GET["tomin"];

        $tempto = "";
        $tempfrom = "";
        $temp = "channel NOT LIKE '%Local%' AND dcontext IN ('city','world','country')";

        //$temp = "1";
        // if(($fromdate != ' 00:00')&&($fromdate != ' :'))
        // {
        //     $temp .= " AND calldate >= '$tempfromdate'";
        // };
        // if(($todate != ' 00:00')&&($todate != ' :'))
        // {
        //     $temp .= " AND calldate <= '$temptodate'";
        // };

        $temp .= " AND calldate BETWEEN '$this->fromdate' AND '$this->todate' ";

        if($_GET["dst"] != "")
        {
            $temp .= " AND dst LIKE '%$_GET[dst]%'";
        };
        if($_GET["src"] != "")
        {
            $temp .= " AND src LIKE '%$_GET[src]%'";
        };
        if(isset($_GET['disposition']) && $_GET["disposition"] != "any")
        {
            $temp .= " AND disposition = '$_GET[disposition]'";
        };


        if ($this->oper) {
            $temp .= "AND ((`dcontext` = 'incoming' AND `dstchannel` = '{$this->oper}')OR (`dcontext` <> 'incoming' AND `userfield`= '{$this->oper}' )) ";
        }


        $sql_req = "SELECT * from cdr WHERE $temp AND LENGTH(src) < 7  AND ((LENGTH(dst) > 7) OR (dst LIKE '#%')) ORDER BY calldate;";
        // $result = $db->query($sql_req) or die ('query');

        $this->dataResult = App::Db()->query($sql_req);

        $this->viewMain('page/page-outgoing.php');
    }
}