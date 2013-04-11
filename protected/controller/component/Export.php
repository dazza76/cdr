<?php

/**
 * Export class  - Export.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * Export class
 *
 * @package		AC
 */
class Export {

    public $type = 'csv';
    /**
     * @var array
     */
    public $thead;
    /**
     * @var array
     */    
    public $data;
    
    

    function __construct(array $data) {
        $this->data = $data;
    }

    public function send($filename = 'data') {
        $filename .= "-".date('Y_m_d').".".$this->type;
        
        header('Content-Type: text/plain; charset=UTF-8');
        header("Content-Disposition: attachment; filename={$filename}");
        
        if ($this->type == 'xls') {
            $this->sendXls($filename);
            return;
        } 
        $this->sendCsv($filename);
        
        
    }

    public function sendXls($filename) {
        //$ctype = "application/octet-stream";
        //         "application/vnd.ms-excel";
        $filename = $filename . ".xls";

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/octet-stream");
        header('Content-Description: File Transfer');
        header("Content-Transfer-Encoding: binary");
        header("Accept-Ranges: bytes");



        $html = "<table>";

        if ($this->thead) {
            $html .= "<thead><tr>";
            foreach ($this->thead as $th) {
                $html .= "<th>{$th}</th>";
            }
            $html .= "</tr></thead>";
        }
        
        $html .= "<tbody>";
        foreach ($this->data as $row) {
            $html .= "<tr>";
            foreach ($row as $td) {
                $html .= "<td>{$td}</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";


        $html .= "</table>";

        echo $html;
    }

    public function sendCsv($filename) {
        $content = "";

        if ($thead = $this->thead) {
            array_walk($thead, array($this, "_quoteValeu"));
            $content = implode(";", $thead)."\n";
        }

        foreach ($this->data as $row) {
            array_walk($row, array($this, "_quoteValeu"));
            $content .= implode(";", $row)."\n";
        }
        
        $content = iconv('utf-8', 'windows-1251', $content);
        
        echo $content;
    }

    private function _quoteValeu(&$str) {
        $str = '"' . str_replace('"', '""', $str) . '"';
        return $str;
    }

}